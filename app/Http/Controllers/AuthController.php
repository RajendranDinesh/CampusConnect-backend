<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;

use App\Models\User;

class AuthController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Find or create the user in your database
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Log in existing user
                Auth::login($existingUser);
                $user = $existingUser;
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(16)),
                    // Add more fields as necessary
                ]);

                Auth::login($user);
            }

            // Generate token for API access using Passport or Sanctum
            $token = $user->createToken('CampusConnect')->plainTextToken;

            if ($user->hasRole('admin')) {
                $redirectUrl = 'http://localhost:5173/admin/';
            } elseif ($user->hasRole('student')) {
                $redirectUrl = 'http://localhost:5173/student/';
            } elseif ($user->hasRole('tnp')) {
                $redirectUrl = 'http://localhost:5173/tnp/';
            } else {
                // Default for regular users
                $redirectUrl = 'http://localhost:5173/user/dashboard';
            }

            return response()->json([
                'token' => $token,
                'redirect_url' => $redirectUrl,
            ]);
     
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to login using Google'], 500);
        }
    }
}

