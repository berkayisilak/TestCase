<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('addProduct', ProductController::class)->middleware('auth:sanctum');

Route::get('/getProducts', [ProductController::class, 'getProducts']);
Route::get('/getProductById/{id}', [ProductController::class, 'getProductById']);
Route::post('/addProduct', [ProductController::class, 'addProduct']);
Route::put('/updateProduct/{id}', [ProductController::class, 'updateProduct']);
Route::delete('/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);


Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
