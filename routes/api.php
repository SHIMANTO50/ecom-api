<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/item/store',[ItemController::class,'store']);
Route::get('/item/show/{id}',[ItemController::class,'show']);
Route::post('/product/store',[ProductController::class,'store']);
Route::post('/cart/store',[CartController::class,'store']);
Route::get('/cart/show',[CartController::class,'show']);
Route::post('/cart/{id}',[CartController::class,'destroy']);
