<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\PortfolioController;
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
                        Route::post('update/{id}', [BannerController::class, 'update'])->name('update');
                        Route::delete('destroy/{id}', [BannerController::class, 'destroy'])->name('destroy');
                    });

                Route::prefix('blog')
                    ->name('blog.')
                    ->group(function () {
                        Route::get('/', [BlogController::class, 'index'])->name('index');
                        Route::get('show/{id}', [BlogController::class, 'show'])->name('show');
                        Route::post('store', [BlogController::class, 'store'])->name('store');
                        Route::post('update/{id}', [BlogController::class, 'update'])->name('update');
                        Route::delete('destroy/{id}', [BlogController::class, 'destroy'])->name('destroy');
                    });

                Route::prefix('portfolio')
                    ->name('portfolio.')
                    ->group(function () {
                        Route::get('/', [PortfolioController::class, 'index'])->name('index');
                        Route::get('show/{id}', [PortfolioController::class, 'show'])->name('show');
                        Route::post('store', [PortfolioController::class, 'store'])->name('store');
                        Route::post('update/{id}', [PortfolioController::class, 'update'])->name('update');
                        Route::delete('destroy/{id}', [PortfolioController::class, 'destroy'])->name('destroy');
                    });
            });
    });
