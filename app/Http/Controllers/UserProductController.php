<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class UserProductController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        $products = Product::with('category')->get();
        return view('user.index', compact('products', 'banners'));
    }
    public function menu()
    {
        $products = Product::with('category')->get();
        return view('user.menu', compact('products'));
    }
}
