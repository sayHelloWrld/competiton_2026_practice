<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::get('/products', [ProductController::class, 'index'])->name('home');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::get('/product/edit/{product}', [ProductController::class, 'edit'])->name('product.edit');

Route::post('/product/create', [ProductController::class, 'store'])->name('product.store');

Route::put('/product/edit/{product}', [ProductController::class, 'update'])->name('product.update');

Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');