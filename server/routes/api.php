<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::post('login', [LoginController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::prefix('profile')
                    ->name('profile.')
                    ->group(function () {
                        Route::get('show', [ProfileController::class, 'show'])->name('show');
                        Route::post('update', [ProfileController::class, 'update'])->name('update');
                    });

                Route::prefix('banner')
                    ->name('banner.')
                    ->group(function () {
                        Route::get('/', [BannerController::class, 'index'])->name('index');
                        Route::get('show/{banner}', [BannerController::class, 'show'])->name('show');

                        Route::post('store', [BannerController::class, 'store'])->name('store');
                        Route::post('update/{banner}', [BannerController::class, 'update'])->name('update');

                        Route::delete('destroy/{id}', [BannerController::class, 'destroy'])->name('destroy');
                    });
            });
    });
