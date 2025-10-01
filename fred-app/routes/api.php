<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\DriverAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/profile', [CustomerAuthController::class, 'profile']);
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
});
Route::prefix('driver')->group(function () {
    Route::post('/login', [DriverAuthController::class, 'login']);
    Route::get('/profile', [DriverAuthController::class, 'profile']);
    Route::post('/logout', [DriverAuthController::class, 'logout']);
});
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/profile', [AdminAuthController::class, 'profile']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);
});

Route::middleware(['auth:admin'])->get('/customers', [CustomerController::class, 'index']);
Route::post('/customers/register', [CustomerController::class, 'register']);

Route::middleware(['auth:admin'])->get('/drivers', [DriverController::class, 'index']);
Route::middleware(['auth:admin'])->post('/drivers/register', [DriverController::class, 'register']);
Route::middleware(['auth:admin'])->get('/payments', [PaymentController::class, 'getall']);
Route::middleware(['auth:customer'])->post('/orders', [OrderController::class, 'create']);
Route::middleware(['auth:customer'])->get('/orders', [OrderController::class, 'myorder']);
Route::middleware(['auth:admin'])->get('/customerorders/{id}', [OrderController::class, 'orderbycustomer']);
Route::middleware(['auth:customer'])->post('/payments', [PaymentController::class, 'index']);
Route::middleware(['auth:customer'])->get('/payments/waiting', [OrderController::class, 'paymentwaiting']);
Route::middleware(['auth:driver,admin'])->patch('/orders/{id}', [OrderController::class, 'delivery']);
Route::middleware(['auth:driver'])->get('/delivery', [OrderController::class, 'getdelivery']);
Route::middleware(['auth:admin'])->get('/allorders', [OrderController::class, 'allorder']);
