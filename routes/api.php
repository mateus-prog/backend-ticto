<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeEntryController;

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

Route::prefix('v1')->group(function(){

    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/unauthorized', [AuthController::class, 'unauthorized']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::middleware(['role:admin'])->group(function () {
            Route::apiResource('user', UserController::class)->only(['store', 'update', 'delete']);
            Route::put('/user/reset/{user}', [UserController::class, 'resetPassword']);
        });

        Route::apiResource('user', UserController::class)->only(['index', 'show']);

        Route::get('/time-entry', [TimeEntryController::class, 'index']);
        Route::post('/time-entry', [TimeEntryController::class, 'store']);

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/password/change', [AuthController::class, 'change']);
    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Esta página não existe.',
        'status' => Response::HTTP_NOT_FOUND
    ], Response::HTTP_NOT_FOUND);
});
