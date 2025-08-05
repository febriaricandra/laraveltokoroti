<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserOrderController extends Controller
{
    public function order()
    {
        $orders = Order::with('orderDetails')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.order', compact('orders'));
    }
    public function show($id)
    {
        $order = Order::with('orderDetails.product')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('user.order-details', compact('order'));
    }

    public function uploadDeliveryProof(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Validate that order can accept delivery proof
        if (!$order->canUploadDeliveryProof()) {
            return redirect()->back()->with('error', 'Order ini tidak dapat menerima bukti pengiriman.');
        }

        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500'
        ]);

        // Delete old proof if exists
        if ($order->delivery_proof && Storage::disk('public')->exists($order->delivery_proof)) {
            Storage::disk('public')->delete($order->delivery_proof);
        }

        // Store new proof
        $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');

        // Update order
        $order->update([
            'delivery_proof' => $path,
            'delivered_at' => now(),
            'status' => Order::STATUS_DELIVERED
        ]);

        return redirect()->back()->with('success', 'Bukti pengiriman berhasil diupload. Terima kasih!');
    }

    public function showDeliveryProofForm($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!$order->canUploadDeliveryProof()) {
            return redirect()->route('user.order.details', $id)
                ->with('error', 'Order ini tidak dapat menerima bukti pengiriman.');
        }

        return view('user.upload-delivery-proof', compact('order'));
    }
}
