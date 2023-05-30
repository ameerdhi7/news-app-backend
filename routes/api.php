<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix("v1")->group(function () {
    // Auth related routes
    Route::middleware('auth:sanctum')
        ->get('/user', function (Request $request) {
            return $request->user();
        });
    Route::post('/login', [ApiTokenController::class, 'login']);
    Route::post('/register', [ApiTokenController::class, 'register']);
    Route::middleware('auth:sanctum')->post('/logout', [ApiTokenController::class, 'logout']);

    // News related routes
    Route::prefix("news")
        ->controller(NewsController::class)
        ->group(function () {
            Route::get("/", "home");
            Route::get("/preferences/options", "preferencesOptions");
            Route::get("/search", "search");
        });
});
