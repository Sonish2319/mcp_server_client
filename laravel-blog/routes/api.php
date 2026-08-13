<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/articles', [ArticleController::class, 'index']);

Route::post('/articles', [ArticleController::class, 'store']);

Route::get('/articles/search', [ArticleController::class, 'search']);

Route::get('/articles/{article}', [ArticleController::class, 'show']);

Route::put('/articles/{article}', [ArticleController::class, 'update']);

Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});