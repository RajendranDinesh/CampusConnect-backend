<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('/admin', function() {
        return response('some', 200);
    });
});
