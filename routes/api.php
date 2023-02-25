<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('confirm-email/{token}', [UserController::class, 'confirmEmail']);
Route::post('register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::post('/orders/cancel/{id}', [OrderController::class, 'cancelOrder']);
    Route::delete('/orders/delete/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/orders/export/csv/{id}', [OrderController::class, 'exportToCsv']);
});



