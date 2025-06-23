<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get();
        return view('user.index', compact('products'));
    }

    public function products()
    {
        $products = Product::with('category')->paginate(12);
        return view('user.products', compact('products'));
    }

    public function menu()
    {
        $products = Product::with('category')->paginate(12);
        return view('user.menu', compact('products'));
    }

    public function contact()
    {
        return view('user.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'subject.required' => 'Subjek wajib diisi',
            'message.required' => 'Pesan wajib diisi',
            'message.min' => 'Pesan minimal 10 karakter',
        ]);

        // Simpan ke database
        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan menghubungi Anda segera.');
    }

    public function order()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with(['orderDetails.product'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('user.order', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::where('user_id', Auth::id())
                     ->where('id', $id)
                     ->with(['orderDetails.product'])
                     ->firstOrFail();
        
        return view('user.order-details', compact('order'));
    }
}
