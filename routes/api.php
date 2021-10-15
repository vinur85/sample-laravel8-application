<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\ProductsController;
use \App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/products', [UserController::class, 'products'])->name('user.products');
    Route::post('user/products', [ProductsController::class, 'update'])->name('user.update');
    Route::delete('user/products/{sku}', [ProductsController::class, 'destroy'])->name('user.delete');
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('auth', [AuthController::class, 'login'])->name('login');
Route::get('products', [ProductsController::class, 'index'])->name('products');
