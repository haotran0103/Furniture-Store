<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SlidersController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['authCheck'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'index']);

    Route::post('/sliders', [SlidersController::class, 'store']);
    Route::post('/sliders/{sliders}', [SlidersController::class, 'update']);
    Route::delete('/sliders/{sliders}', [SlidersController::class, 'destroy']);

    Route::post('/settings', [SettingController::class, 'store']);
    Route::post('/settings/{setting}', [SettingController::class, 'update']);
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy']);

    Route::post('/products', [productsController::class, 'store']);
    Route::post('/products/{products}', [productsController::class, 'update']);
    Route::delete('/products/{products}', [productsController::class, 'destroy']);

    Route::post("admin/tags", [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

    Route::post("admin/categories", [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::post('/menu', [menuController::class, 'store']);
    Route::post('/menu/{menu}', [menuController::class, 'update']);
    Route::delete('/menu/{menu}', [menuController::class, 'destroy']);

    Route::post('/coupons', [CouponController::class, 'store']);
    Route::put('/admin/coupons/{id}', [CouponController::class, 'update']);
    Route::delete('/admin/coupons/{id}', [CouponController::class, 'destroy']);

    Route::get('/dashboard/data', [DashboardController::class, 'getData']);

    Route::post('/admin/logout', [UserController::class, 'logout']);
});

Route::get("admin/categories", [CategoryController::class, 'index']);
Route::get("admin/tags", [TagController::class, 'index']);



Route::get('/products', [productsController::class, 'index']);
Route::get('/products-all', [productsController::class, 'getAll']);
Route::get('/products/suggestions/{productId}', [productsController::class, 'suggestions']);
Route::get('/products/category/{categoryID}', [productsController::class, 'productsByCategory']);
Route::get('/products/{productId}', [productsController::class, 'show']);
Route::get('/products-detail/{id}', [productsController::class, 'detail']);


Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/{setting}', [SettingController::class, 'show']);


Route::get('/sliders', [SlidersController::class, 'index']);
Route::get('/sliders/{sliders}', [SlidersController::class, 'show']);



Route::post('/customer/register', [CustomerController::class, 'register']);
Route::post('/customer/login', [CustomerController::class, 'login']);
Route::get('/customer/profile', [CustomerController::class, 'profile']);
Route::put('/customer/profile', [CustomerController::class, 'update']);
Route::post('/customer/change-password', [CustomerController::class, 'changePassword']);


Route::get('/menu', [menuController::class, 'index']);
Route::get('/menu-all', [menuController::class, 'getAllMenu']);
Route::get('/menu/{menu}', [menuController::class, 'show']);

Route::get('/orders', [CheckoutController::class, 'index']);
Route::post('/orders', [CheckoutController::class, 'store']);
Route::get('/admin/orders', [CheckoutController::class, 'getAll']);
Route::get('/admin/orders/success', [CheckoutController::class, 'getSucccess']);
Route::patch('/admin/orders/{orderId}/confirm', [CheckoutController::class, 'confirmOrder']);
Route::patch('/orders/{orderId}/cancel', [CheckoutController::class, 'cancelOrder']);


Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/{id}', [CouponController::class, 'applyCoupon']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);



Route::get('/featured-products', [productsController::class, 'featuredProducts']);
Route::get('/popular-tags', [productsController::class, 'popularTags']);
Route::get('/products-random', [productsController::class, 'productsRandom']);
Route::get('/products-by-category/{id}', [productsController::class, 'productsWithCategory']);

