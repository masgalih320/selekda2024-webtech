<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::post('login', [App\Http\Controllers\API\Auth\LoginController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::get('profile', [App\Http\Controllers\API\ProfileController::class, '__invoke'])->name('profile');
            });
    });
