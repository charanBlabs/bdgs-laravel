<?php

use App\Http\Controllers\BlabsReviewController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\WebinarsController;
use App\Http\Middleware\ProvideMarkdownResponse;
use Illuminate\Support\Facades\Route;

Route::middleware(ProvideMarkdownResponse::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/index.md', [HomeController::class, 'index']);

    Route::get('/services', [ServicesController::class, 'index']);
    Route::get('/services/', [ServicesController::class, 'index'])->name('services');
    Route::get('/services.md', [ServicesController::class, 'index']);

    Route::get('/customization', [CustomizationController::class, 'index'])->name('customization');
    Route::get('/customization/', [CustomizationController::class, 'index']);
    Route::get('/customization.md', [CustomizationController::class, 'index']);

    Route::get('/blabs-review', [BlabsReviewController::class, 'index'])->name('blabs-review');
    Route::get('/blabs-review/', [BlabsReviewController::class, 'index']);
    Route::get('/blabs-review.md', [BlabsReviewController::class, 'index']);

    Route::get('/webinars', [WebinarsController::class, 'index'])->name('webinars');
    Route::get('/webinars/', [WebinarsController::class, 'index']);
    Route::get('/webinars.md', [WebinarsController::class, 'index']);
});
