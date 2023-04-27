<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/card-sign-in', [AuthController::class, 'login']);
Route::post('/standard-sign-in', [AuthController::class, 'standardLogin']);
Route::post('/pin-verification', [AuthController::class, 'pinVerification']);


Route::middleware('auth:sanctum')->group(function () {
    //Auth
    Route::post('/sign-out', [AuthController::class, 'logout']);

    //Card
    Route::post('/generate-new-card', [CardController::class, 'generateNewCard']);

    //Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'create']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);

    //Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/users/{id}/orders', [OrderController::class, 'indexByUser']);
    Route::post('/orders', [OrderController::class, 'create']);

    //Transaction
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::post('/make-purchase', [TransactionController::class, 'purchase']);
    Route::post('/make-topup', [TransactionController::class, 'topup']);
});

