<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

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
}
