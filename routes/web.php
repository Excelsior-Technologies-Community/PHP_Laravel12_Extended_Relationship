<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// List Products - name fixed
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Create Product form
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Store Product
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');