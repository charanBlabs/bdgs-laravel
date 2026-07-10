<?php

use App\Http\Controllers\Api\InquirySubmitController;
use App\Http\Controllers\Api\ReviewsListController;
use App\Http\Controllers\Api\ReviewsSyncController;
use App\Http\Controllers\Api\ZoomClinicRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/reviews/list', [ReviewsListController::class, 'index']);
Route::get('/reviews/count', [ReviewsListController::class, 'count']);

Route::post('/reviews/sync', [ReviewsSyncController::class, 'handle'])
    ->middleware(['reviews.sync', 'throttle:reviews-sync'])
    ->withoutMiddleware('throttle:api');

Route::post('/zoom-clinics/register', [ZoomClinicRegisterController::class, 'store'])
    ->middleware('throttle:30,1');

Route::get('/inquiry', [InquirySubmitController::class, 'spec']);

Route::post('/inquiry/submit', [InquirySubmitController::class, 'store'])
    ->middleware(['inquiry.agent', 'throttle:6,1']);
