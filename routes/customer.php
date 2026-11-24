<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Middleware\CustomerSessionOnlyCache;
use Illuminate\Support\Facades\Route;

Route::prefix('pelanggan')->name('customer.')->middleware(CustomerSessionOnlyCache::class)->group(function () {
    Route::get('/', [CustomerController::class, 'home'])->block()->name('home');
    Route::get('/menu/{category?}', [CustomerController::class, 'catalog'])->block()->name('catalog');
    Route::get('/item/{item}', [CustomerController::class, 'item'])->whereNumber('item')->block()->name('item');
    Route::post('/meja', [CustomerController::class, 'table'])->block()->name('table');
    Route::get('/pesanan', [CustomerController::class, 'cart'])->block()->name('cart');
    Route::get('/keranjang', [CustomerController::class, 'cartState'])->block()->name('cart.state');
    Route::post('/keranjang', [CustomerController::class, 'updateCart'])->block()->name('cart.update');
    Route::get('/pembayaran', [CustomerController::class, 'checkout'])->block()->name('checkout');
    Route::post('/pembayaran', [CustomerController::class, 'storeOrder'])->block(30, 10)->name('checkout.store');
    Route::get('/pembayaran/{number}', [CustomerController::class, 'payment'])->block()->name('payment');
    Route::post('/pembayaran/{number}/status', [CustomerController::class, 'paymentStatus'])->block(30, 10)->name('payment.status');
    Route::post('/pembayaran/{number}/simulasi', [CustomerController::class, 'pay'])->block()->name('payment.simulate');
    Route::post('/pembayaran/{number}/batal', [CustomerController::class, 'cancel'])->block()->name('payment.cancel');
    Route::get('/pembayaran-berhasil/{number?}', [CustomerController::class, 'success'])->block()->name('success');
    Route::get('/detail-pesanan/{number?}', [CustomerController::class, 'order'])->block()->name('order');
    Route::get('/informasi', [CustomerController::class, 'outlet'])->block()->name('outlet');
});

Route::post('/payments/midtrans/notification', MidtransNotificationController::class)->name('payments.midtrans.notification');
