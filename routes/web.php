<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TelemetryController;

Route::get('/', fn() => redirect()->route('products.index'));

Route::resource('products', ProductController::class);
Route::resource('managers', ManagerController::class)->except(['show']);
Route::resource('tags', TagController::class)->only(['index', 'store', 'destroy']);

Route::get('/telemetry', [TelemetryController::class, 'index'])->name('telemetry.index');
