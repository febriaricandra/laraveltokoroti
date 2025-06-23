<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use \PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'orderDetails.product')->latest()->paginate(10);
        return view('admin.order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderDetails.product');
        return view('admin.order.show', compact('order'));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,send,finish',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.order.index')->with('success', 'Status order diperbarui.');
    }
    public function printOrderList()
    {
        $orders = Order::with('orderDetails')->get();
        $pdf = PDF::loadView('admin.order.pdf', compact('orders'));
        return $pdf->download('daftar_order.pdf');
    }
    public function destroy(Order $order)
    {
        if ($order->payment_proof && Storage::exists('public/' . $order->payment_proof)) {
            Storage::delete('public/' . $order->payment_proof);
        }

        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Order berhasil dihapus.');
    }
}
