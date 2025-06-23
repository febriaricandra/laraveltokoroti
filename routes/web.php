<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserCartController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\InvoiceController;

// Public routes
Route::get('/', [UserProductController::class, 'index'])->name('user.products');
Route::get('/menu', [UserProductController::class, 'menu'])->name('user.menu');
Route::get('/contact', [UserController::class, 'contact'])->name('user.contact');
Route::post('/contact', [UserController::class, 'submitContact'])->name('user.contact.submit');
Route::get('order/print', [OrderController::class, 'printOrderList'])->name('admin.order.print');

// Cart routes (dapat diakses tanpa login, tapi akan redirect ke login saat checkout)
Route::post('/cart/store', [UserCartController::class, 'store'])->name('cart.store');
Route::get('/cart', [UserCartController::class, 'index'])->name('cart.index');
Route::put('/cart/update/{id}', [UserCartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [UserCartController::class, 'destroy'])->name('cart.destroy');
Route::post('/checkout', [UserCartController::class, 'checkout'])->name('cart.checkout');
Route::get('/order', [UserOrderController::class, 'order'])->name('user.order')->middleware('auth');
Route::get('/order/{id}', [UserOrderController::class, 'show'])->name('user.order.details');

// Invoice PDF
Route::get('/orders/{orderId}/invoice', [InvoiceController::class, 'generateInvoicePdf'])->name('user.order.invoice');


// Auth routes - Customer
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Auth routes - Admin
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'adminCreate'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'adminStore'])->name('admin.login.store');
});

// Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Customer routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Order routes (harus login)
    Route::get('/order', [UserOrderController::class, 'order'])->name('user.order');
    Route::get('/order/{id}', [UserOrderController::class, 'show'])->name('user.order.details');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('product', ProductController::class);

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{contact}', [ContactController::class, 'show'])->name('contact.show');
    Route::delete('/contact/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');

    Route::resource('discounts', DiscountController::class);
    Route::resource('banners', BannerController::class);
});

// Dashboard route (redirect based on role)
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return view('dashboard');
    }
    return redirect()->route('user.products');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
