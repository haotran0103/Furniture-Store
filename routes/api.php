<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
*/


Route::get("admin/categories", [CategoryController::class, 'index']);
Route::post("admin/categories", [CategoryController::class, 'store']);
