<?php

use App\Http\Controllers\CustomerDemoController;
use App\Http\Middleware\CustomerDemoOnly;
use Illuminate\Support\Facades\Route;

Route::prefix('pelanggan')->name('customer.')->middleware(CustomerDemoOnly::class)->group(function () {
    Route::get('/', [CustomerDemoController::class, 'home'])->name('home');
    Route::get('/menu/{category?}', [CustomerDemoController::class, 'catalog'])->name('catalog');
    Route::get('/item/{item}', [CustomerDemoController::class, 'item'])->name('item');
    Route::get('/pesanan', [CustomerDemoController::class, 'cart'])->name('cart');
    Route::get('/pembayaran', [CustomerDemoController::class, 'checkout'])->name('checkout');
    Route::get('/pembayaran-berhasil', [CustomerDemoController::class, 'success'])->name('success');
    Route::get('/detail-pesanan', [CustomerDemoController::class, 'order'])->name('order');
    Route::get('/informasi', [CustomerDemoController::class, 'outlet'])->name('outlet');
});
