<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('blog.index');
});

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/articles/create', [BlogController::class, 'create'])
    ->name('blog.create');

Route::post('/blog/articles', [BlogController::class, 'store'])
    ->name('blog.store');

Route::get('/blog/articles/{article}', [BlogController::class, 'show'])
    ->name('blog.show');

Route::get('/blog/articles/{article}/edit', [BlogController::class, 'edit'])
    ->name('blog.edit');

Route::put('/blog/articles/{article}', [BlogController::class, 'update'])
    ->name('blog.update');

Route::delete('/blog/articles/{article}', [BlogController::class, 'destroy'])
    ->name('blog.destroy');