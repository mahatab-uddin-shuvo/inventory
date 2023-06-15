<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\PurchasesController;
use App\Http\Controllers\Inventory\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');


    Route::get('products',[ProductController::class, 'list'])->name('product.list');

    Route::prefix('sales')->group(function () {
        Route::post('create', [SalesController::class, 'create'])->name('sales.create');
    });
    Route::prefix('purchases')->group(function () {
        Route::post('create', [PurchasesController::class, 'create'])->name('purchases.create');
    });
});
