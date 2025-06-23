<?php

use Illuminate\Support\Facades\Route;

// Controller untuk Customer
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// Controller untuk Admin
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\PromotionController as PublicPromotionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//==========================================================================
// RUTE UNTUK CUSTOMER (PUBLIK, TANPA LOGIN)
//==========================================================================

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Rute Keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/update/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/keranjang/hapus/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/keranjang/tambah-bundel/{promotion}', [CartController::class, 'addBundle'])->name('cart.addBundle');

Route::post('/set-order-options', [CartController::class, 'setOrderOptions'])->name('cart.setOrderOptions');
Route::get('/promo/{promotion}', [PublicPromotionController::class, 'show'])->name('promo.show');

// Rute Proses Checkout & Pembayaran
Route::post('/pesanan/buat', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesanan/{order:order_code}', [OrderController::class, 'show'])->name('order.show');
Route::get('/payment/ewallet/{order:order_code}', [PaymentController::class, 'ewallet'])->name('payment.ewallet');
Route::get('/payment/epayment/{order:order_code}', [PaymentController::class, 'epayment'])->name('payment.epayment');
Route::get('/payment/cancel/{order:order_code}', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Rute untuk Memproses Aksi Pembayaran (Simulasi)
Route::post('/payment/process/{order:order_code}', [PaymentController::class, 'process'])->name('payment.process'); // Untuk E-Wallet
Route::post('/payment/epayment/{order:order_code}/add', [PaymentController::class, 'addCard'])->name('payment.add_card');
Route::post('/payment/epayment/{order:order_code}/pay', [PaymentController::class, 'processWithSavedCard'])->name('payment.pay_with_saved'); // Untuk kartu tersimpan

//==========================================================================
// RUTE UNTUK ADMIN
//==========================================================================

Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Login & Logout khusus Admin (tidak dilindungi middleware)
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Rute Admin yang Dilindungi (harus login sebagai admin dulu)
    Route::middleware('is_admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Rute untuk Manajemen Produk (CRUD)
        Route::resource('products', AdminProductController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('tables', TableController::class);

        // Rute untuk Manajemen Pesanan
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});
