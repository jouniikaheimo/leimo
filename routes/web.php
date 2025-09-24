<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThemeColorController;

Route::get('/', function () {
    return view('welcome');
});

// API route for theme colors
Route::get('/api/theme-colors', [ThemeColorController::class, 'index'])->name('api.theme-colors');
