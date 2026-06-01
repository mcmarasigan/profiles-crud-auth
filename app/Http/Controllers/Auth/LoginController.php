<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Handles user login and logout with rate limiting and session security.
 */
class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login form POST submission.
     * Implements rate limiting to prevent brute-force attacks.
     */
    public function login(LoginRequest $request)
    {
        // --- Rate Limiting: max 5 attempts per minute per IP+email ---
        $key = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Too many login attempts. Please wait {$seconds} seconds before trying again.",
                ]);
        }

        // Attempt authentication using email/password
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limit on successful login
            RateLimiter::clear($key);

            // Regenerate session ID to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . e(Auth::user()->name) . '!');
        }

        // Increment failed attempt counter
        RateLimiter::hit($key, 60);
        $attemptsLeft = RateLimiter::retriesLeft($key, 5);

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => "These credentials do not match our records. You have {$attemptsLeft} attempt(s) left.",
            ]);
    }

    /**
     * Log the user out and destroy the session completely.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
