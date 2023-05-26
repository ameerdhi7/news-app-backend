<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth related routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/login', [ApiTokenController::class, 'login']);
Route::post('/auth/register', [ApiTokenController::class, 'register']);
Route::middleware('auth:sanctum')->post('/auth/logout', [ApiTokenController::class, 'logout']);

// News related routes
Route::prefix("v1")->group(function () {
    Route::prefix("news")
        ->controller(NewsController::class)
        ->group(function () {
            Route::get("/", "home");
        });
});
