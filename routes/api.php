<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['role:student', 'auth:sanctum'])->group(function () {
    Route::get('/student', function() {
        return response('some', 200);
    });
});
