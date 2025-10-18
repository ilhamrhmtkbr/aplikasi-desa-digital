<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('dashboard/get-dashboard-data', [\App\Http\Controllers\DashboardController::class, 'getDashboardData']);

    Route::get('user/all/paginated', [\App\Http\Controllers\UserController::class, 'getAllPaginated']);
    Route::apiResource('user', \App\Http\Controllers\UserController::class);

    Route::get('head-of-family/all/paginated', [\App\Http\Controllers\HeadOfFamilyController::class, 'getPaginated']);
    Route::apiResource('head-of-family', \App\Http\Controllers\HeadOfFamilyController::class);

    Route::get('family-member/all/paginated', [\App\Http\Controllers\FamilyMemberController::class, 'getPaginated']);
    Route::apiResource('family-member', \App\Http\Controllers\FamilyMemberController::class);

    Route::get('social-assistance/all/paginated', [\App\Http\Controllers\SocialAssistanceController::class, 'getPaginated']);
    Route::apiResource('social-assistance', \App\Http\Controllers\SocialAssistanceController::class);

    Route::get('social-assistance-recipient/all/paginated', [\App\Http\Controllers\SocialAssistanceRecipientsController::class, 'getPaginated']);
    Route::apiResource('social-assistance-recipient', \App\Http\Controllers\SocialAssistanceRecipientsController::class);

    Route::get('event/all/paginated', [\App\Http\Controllers\EventController::class, 'getPaginated']);
    Route::apiResource('event', \App\Http\Controllers\EventController::class);

    Route::get('event-participant/all/paginated', [\App\Http\Controllers\EventParticipantController::class, 'getPaginated']);
    Route::apiResource('event-participant', \App\Http\Controllers\EventParticipantController::class);

    Route::get('development/all/paginated', [\App\Http\Controllers\DevelopmentController::class, 'getPaginated']);
    Route::apiResource('development', \App\Http\Controllers\DevelopmentController::class);

    Route::get('development-applicant/all/paginated', [\App\Http\Controllers\DevelopmentApplicantController::class, 'getPaginated']);
    Route::apiResource('development-applicant', \App\Http\Controllers\DevelopmentApplicantController::class);

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index']);
    Route::post('profile', [\App\Http\Controllers\ProfileController::class, 'store']);
    Route::patch('profile', [\App\Http\Controllers\ProfileController::class, 'update']);
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
   Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
   Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me'])->name('me');
});

// routes/api.php
Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'message' => 'Authenticated!'
    ]);
});

Route::post('/midtrans-callback', [\App\Http\Controllers\MidtransController::class, 'callback']);
