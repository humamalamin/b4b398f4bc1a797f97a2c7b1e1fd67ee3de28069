<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\ReservationStatusController;
use App\Http\Controllers\API\TableModelController;
use App\Http\Controllers\API\WalkInController;
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

Route::group(['prefix'=> 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('tables')->group(function () {
        Route::get('/', [TableModelController::class,'index']);
        Route::post('/', [TableModelController::class,'store']);
        Route::put('/{id}', [TableModelController::class,'update']);
        Route::delete('/{id}/delete', [TableModelController::class,'destroy']);
        Route::get('/{id}', [TableModelController::class,'show']);
    });

    Route::prefix('reservation-statuses')->group(function () {
        Route::get('/', [ReservationStatusController::class,'index']);
    });

    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class,'index']);
        Route::post('/', [ReservationController::class,'store']);
        Route::put('/{id}', [ReservationController::class,'update']);
        Route::delete('/{id}/delete', [ReservationController::class,'destroy']);
        Route::get('/{id}', [ReservationController::class,'show']);
    });

    Route::prefix('walk-ins')->group(function () {
        Route::get('/', [WalkInController::class,'index']);
        Route::post('/', [WalkInController::class,'store']);
        Route::put('/{id}', [WalkInController::class,'update']);
        Route::delete('/{id}/delete', [WalkInController::class,'destroy']);
        Route::get('/{id}', [WalkInController::class,'show']);
    });
});
