<?php

use App\Api\V1\Controllers\AuthController;
use App\Api\V1\Controllers\CustomerController;
use App\Http\Middleware\AdminAccessMiddleware;
use App\Http\Middleware\TooManyAttemptsMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['prefix' => 'customers', 'middleware' => [TooManyAttemptsMiddleware::class]], function () {
        Route::post('verify', [CustomerController::class, 'verify']);
        Route::post('check-token', [CustomerController::class, 'checkToken']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::delete('refresh-token', [AuthController::class, 'refreshToken']);
        Route::apiResource('customers', CustomerController::class)->middleware(AdminAccessMiddleware::class);
    });
});
