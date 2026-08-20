<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::get('/articles', [ArticleController::class, 'index']);

Route::post('/articles', [ArticleController::class, 'store']);

Route::get('/articles/search', [ArticleController::class, 'search']);

Route::get('/articles/{article}', [ArticleController::class, 'show']);

Route::put('/articles/{article}', [ArticleController::class, 'update']);

Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});