<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // User sudah ada, update google_id dan avatar
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // User baru, buat dengan random password
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            // Login user
            Auth::login($user, true); // true = remember me

            // Redirect ke dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google Login Error: ' . $e->getMessage());

            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::updateOrCreate(
                ['email' => $facebookUser->email],
                [
                    'name' => $facebookUser->name,
                    'facebook_id' => $facebookUser->id,
                    'avatar' => $facebookUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            Auth::login($user);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Facebook. Silakan coba lagi.');
        }
    }

    /**
     * Redirect to Apple OAuth
     */
    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    /**
     * Handle Apple OAuth callback
     */
    public function handleAppleCallback()
    {
        try {
            $appleUser = Socialite::driver('apple')->user();

            $user = User::updateOrCreate(
                ['email' => $appleUser->email],
                [
                    'name' => $appleUser->name ?? 'Apple User',
                    'apple_id' => $appleUser->id,
                    'avatar' => $appleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            Auth::login($user);

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Apple. Silakan coba lagi.');
        }
    }
}
