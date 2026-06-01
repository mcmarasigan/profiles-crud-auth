<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Handles new user registration.
 * Passwords are hashed using bcrypt (via Laravel's Hash facade).
 * CSRF protection is handled automatically by Laravel middleware.
 */
class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showForm()
    {
        // Redirect already authenticated users to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle the registration form POST submission.
     * All input is validated via RegisterRequest before reaching this method.
     */
    public function register(RegisterRequest $request)
    {
        // Create the user — password is automatically hashed via model cast
        $user = User::create([
            'name'     => strip_tags($request->name),   // XSS prevention
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Secure bcrypt hash
            'role'     => 'user', // New registrations are always 'user' role
        ]);

        // Authenticate the new user and regenerate session (prevent session fixation)
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Welcome, ' . e($user->name) . '! Your account has been created.');
    }
}
