<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Discount;
use App\Models\User;
use App\Models\ProductSize;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\InvoiceController;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\RajaOngkirService;



class UserCartController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login untuk menambahkan ke keranjang.'], 401);
        }

        // Validate the required fields
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if this exact product and size combination exists in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();

        if ($cartItem) {
            // If exists, increment quantity by the requested amount
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // If not, create new cart item with the specified quantity
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'product_size_id' => $request->product_size_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang!'], 200);
    }

    public function index()
    {
        // Load cart items with product and size relationships
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'size'])
            ->get();

        $subtotalAmount = 0;

        foreach ($cartItems as $item) {
            // Get price from productSize instead of product
            $price = $item->size ? $item->size->price : 0;
            $subtotalAmount += $price * $item->quantity;
        }

        $activeDiscounts = Discount::where('is_active', true)
            ->orderBy('discount_percentage', 'desc')
            ->orderBy('minimum_order', 'asc')
            ->get();

        $appliedDiscount = null;
        $discountAmount = 0;
        $finalTotal = $subtotalAmount;
        $discountPercentageApplied = 0;

        foreach ($activeDiscounts as $discount) {
            if ($subtotalAmount >= $discount->minimum_order) {
                $appliedDiscount = $discount;
                $discountAmount = $subtotalAmount * $discount->discount_percentage;
                $finalTotal = $subtotalAmount - $discountAmount;
                $discountPercentageApplied = $discount->discount_percentage;
                break;
            }
        }

        // Get RajaOngkir data for shipping calculation
        try {
            $rajaOngkirService = new RajaOngkirService();
            $provinces = $rajaOngkirService->getProvinces();
            $provincesData = $provinces['data'] ?? [];
        } catch (\Exception $e) {
            $provincesData = [];
            Log::warning('Failed to load provinces for cart: ' . $e->getMessage());
        }

        $isShippingEnabled = Setting::get('enable_shipping_cost', false);

        return view('user.cart', compact(
            'cartItems', 
            'subtotalAmount', 
            'appliedDiscount', 
            'discountAmount', 
            'finalTotal', 
            'discountPercentageApplied',
            'provincesData',
            'isShippingEnabled'
        ));
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);

        // Make sure the cart item belongs to the current user
        if ($cartItem->user_id != Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Anda tidak memiliki akses untuk mengubah item ini.');
        }

        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
    }

    public function checkout(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:cash,transfer',
            'is_down_payment' => 'nullable|boolean',
            'customer_province_id' => 'nullable|integer',
            'customer_city_id' => 'nullable|integer',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_courier' => 'nullable|string',
            'shipping_service' => 'nullable|string',
            'shipping_weight' => 'nullable|integer|min:1',
        ];

        if ($request->payment_method === 'transfer') {
            $validationRules['payment_proof'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($validationRules);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product', 'size'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $subtotalAmount = 0;
        foreach ($cartItems as $item) {
            // Get price from size instead of product
            $price = $item->size ? $item->size->price : 0;
            $subtotalAmount += $price * $item->quantity;
        }

        $activeDiscounts = Discount::where('is_active', true)
            ->orderBy('discount_percentage', 'desc')
            ->orderBy('minimum_order', 'asc')
            ->get();

        $appliedDiscount = null;
        $discountAmount = 0;
        $finalTotal = $subtotalAmount;
        $discountPercentageApplied = 0;

        foreach ($activeDiscounts as $discount) {
            if ($subtotalAmount >= $discount->minimum_order) {
                $appliedDiscount = $discount;
                $discountAmount = $subtotalAmount * $discount->discount_percentage;
                $finalTotal = $subtotalAmount - $discountAmount;
                $discountPercentageApplied = $discount->discount_percentage;
                break;
            }
        }

        // Add shipping cost
        $shippingCost = $request->shipping_cost ?? 0;
        $finalTotal += $shippingCost;

        // Handle down payment
        $isDownPayment = $request->has('is_down_payment') && $request->is_down_payment;
        $downPaymentAmount = 0;
        $remainingAmount = 0;
        
        if ($isDownPayment) {
            // Down payment is 50% of total including shipping
            $downPaymentAmount = $finalTotal * 0.5;
            $remainingAmount = $finalTotal - $downPaymentAmount;
        }

        DB::beginTransaction();

        try {
            $proofPath = null;
            if ($request->payment_method === 'transfer' && $request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Determine order status
            $orderStatus = 'pending';
            if ($request->payment_method === 'transfer') {
                if ($isDownPayment) {
                    $orderStatus = 'dp_paid'; // Down payment paid
                } else {
                    $orderStatus = 'paid'; // Full payment
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'payment_proof' => $proofPath,
                'payment_method' => $request->payment_method,
                'status' => $orderStatus,
                'discount_percentage' => $discountPercentageApplied,
                'total_discount' => $discountAmount,
                'total_price' => $finalTotal,
                'is_down_payment' => $isDownPayment,
                'down_payment_amount' => $isDownPayment ? $downPaymentAmount : null,
                'remaining_amount' => $isDownPayment ? $remainingAmount : null,
                'customer_province_id' => $request->customer_province_id,
                'customer_city_id' => $request->customer_city_id,
                'shipping_cost' => $shippingCost,
                'shipping_courier' => $request->shipping_courier,
                'shipping_service' => $request->shipping_service,
                'shipping_weight' => $request->shipping_weight ?? 1000,
            ]);

            foreach ($cartItems as $item) {
                // Get size details for order history
                $size = $item->productSize;
                $sizeName = $size ? $size->size : null;
                $price = $size ? $size->price : 0;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'price' => $price, // Use the price from product size
                ]);
            }

            Cart::where('user_id', $user->id)->delete();
            $admins = User::where('email', 'test@example.com')->get(); 
            Notification::send($admins, new NewOrderNotification($order));
            DB::commit();

            $successMessage = 'Checkout berhasil! Pesanan kamu sedang diproses.';
            if ($isDownPayment) {
                $successMessage .= ' Kamu telah membayar DP sebesar Rp' . number_format($downPaymentAmount, 0, ',', '.') . 
                                  '. Sisa pembayaran: Rp' . number_format($remainingAmount, 0, ',', '.');
            }
            $successMessage .= ' Invoice Anda akan diunduh secara otomatis.';

            return redirect()->route('user.order.details', $order->id)
                ->with('success', $successMessage)
                ->with('download_invoice', true);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout failed: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Checkout gagal. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    public function getCities($provinceId)
    {
        try {
            $rajaOngkirService = new RajaOngkirService();
            $cities = $rajaOngkirService->getCities($provinceId);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer',
            'weight' => 'required|integer|min:1'
        ]);

        try {
            $rajaOngkirService = new RajaOngkirService();
            
            // Check if same city (free shipping)
            if ($rajaOngkirService->isSameCity($request->city_id)) {
                return response()->json([
                    'same_city' => true,
                    'shipping_cost' => 0,
                    'message' => 'Gratis ongkir - Satu kota dengan toko'
                ]);
            }

            $shippingCosts = $rajaOngkirService->calculateShippingCosts($request->city_id, $request->weight);
            
            return response()->json([
                'same_city' => false,
                'shipping_options' => $shippingCosts
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
