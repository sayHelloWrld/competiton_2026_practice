<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::post('/user/signup', [AuthController::class, 'signup']);
    Route::post('/user/login', [AuthController::class, 'login']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user/details', [AuthController::class, 'userDetails']);
        Route::post('/user/logout/all', [AuthController::class, 'logoutAll']);
        Route::post('/user/logout', [AuthController::class, 'logout']);
        Route::get('/user/tokens', [AuthController::class, 'getTokens']);
        Route::delete('/user/token/{id}', [AuthController::class, 'deleteToken']);
        
        Route::post('/product', [ProductController::class, 'store']);
        Route::patch('/product/{id}', [ProductController::class, 'update']);
        Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    });
});
