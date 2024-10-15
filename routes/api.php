<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EndorsementController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ExperienceRoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['role:student', 'auth:sanctum'])->group(function () {
    Route::get('users/{userId}/projects', [ProjectController::class, 'getUserProjects']);
    Route::get('users/projects', [ProjectController::class, 'getAuthenticatedUserProjects']);
    Route::resource('projects', ProjectController::class);

    Route::get('users/{userId}/experiences', [ExperienceController::class, 'getUserExperiences']);
    Route::get('users/experiences', [ExperienceController::class, 'getAuthenticatedUserExperiences']);
    Route::resource('experiences', ExperienceController::class);

    Route::get('exp/{experienceId}/roles', [ExperienceRoleController::class, 'index']);
    Route::resource('exp/roles', ExperienceRoleController::class);

    Route::resource('exp/skills', SkillController::class);

    Route::get('user/{userId}/endorsements', [EndorsementController::class, 'show']);
    Route::resource('endorsements', EndorsementController::class);
});
