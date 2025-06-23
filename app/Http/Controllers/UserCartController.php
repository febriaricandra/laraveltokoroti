<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InvoiceController;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;


class UserCartController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login untuk menambahkan ke keranjang.'], 401);
        }
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang!'], 200);
    }

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $subtotalAmount = 0;
        foreach ($cartItems as $item) {
            $subtotalAmount += $item->product->price * $item->quantity;
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
            'payment_method' => 'required|in:cash,transfer',
        ];

        if ($request->payment_method === 'transfer') {
            $validationRules['payment_proof'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($validationRules);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $subtotalAmount = 0;
        foreach ($cartItems as $item) {
            $subtotalAmount += $item->product->price * $item->quantity;
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
                'payment_proof' => $proofPath,
                'payment_method' => $request->payment_method,
                'status' => 'pending',

                'discount_percentage' => $discountPercentageApplied,
                'total_discount' => $discountAmount,
                'total_price' => $finalTotal,
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

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
