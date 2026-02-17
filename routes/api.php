<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('customers')->group(function () {
    Route::post('/register', [CustomerController::class, 'register']);
    Route::post('/login', [CustomerController::class, 'login']);
});

// Protected Routes
Route::middleware('auth:sanctum')->prefix('customers')->group(function () {
    Route::get('/me', [CustomerController::class, 'me']);
    Route::put('/profile', [CustomerController::class, 'updateProfile']);
    Route::post('/change-password', [CustomerController::class, 'changePassword']);
    Route::post('/logout', [CustomerController::class, 'logout']);
    Route::post('/logout-all', [CustomerController::class, 'logoutAll']);
});

