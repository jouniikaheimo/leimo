<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Api\ThemeColorController;

Route::get('/', function () {
    return view('leimo');
})->name('home');

// API route for theme colors
Route::get('/api/theme-colors', [ThemeColorController::class, 'index'])->name('api.theme-colors');

Route::post('/locale', LocaleController::class)->name('locale.switch');
