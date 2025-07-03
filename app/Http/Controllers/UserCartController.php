<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Discount;
use App\Models\User;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InvoiceController;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;



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

        return view('user.cart', compact('cartItems', 'subtotalAmount', 'appliedDiscount', 'discountAmount', 'finalTotal', 'discountPercentageApplied'));
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
            'phone' => 'required|string|max:15', // Added phone validation
            'payment_method' => 'required|in:cash,transfer',
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

        DB::beginTransaction();

        try {
            $proofPath = null;
            if ($request->payment_method === 'transfer' && $request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone, // Added phone field
                'payment_proof' => $proofPath,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'transfer' ? 'paid' : 'pending',
                'discount_percentage' => $discountPercentageApplied,
                'total_discount' => $discountAmount,
                'total_price' => $finalTotal,
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

            return redirect()->route('user.order.details', $order->id)
                ->with('success', 'Checkout berhasil! Pesanan kamu sedang diproses. Invoice Anda akan diunduh secara otomatis.')
                ->with('download_invoice', true);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout failed: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Checkout gagal. Silakan coba lagi. ' . $e->getMessage());
        }
    }
}
