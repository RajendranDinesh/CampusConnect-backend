<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['role:student', 'auth:sanctum'])->group(function () {
    Route::get('users/{userId}/projects', [ProjectController::class, 'getUserProjects']);
    Route::get('users/projects', [ProjectController::class, 'getAuthenticatedUserProjects']);
    Route::resource('projects', ProjectController::class);
});
