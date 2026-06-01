<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Handles forgot password and password reset via email token.
 * BONUS FEATURE: Password reset with secure token (expires in 60 minutes).
 */
class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to email.
     * We always return success to prevent email enumeration attacks.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // If user exists, create token and send email
        if ($user) {
            // Delete any existing token for this email
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Generate a secure random token
            $token = Str::random(64);

            // Store hashed token in database
            DB::table('password_reset_tokens')->insert([
                'email'      => $request->email,
                'token'      => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

            // Build reset URL
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $request->email,
            ]);

            // Send email (logged in dev — check storage/logs/laravel.log)
            Mail::send('emails.password-reset', [
                'user'     => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Reset Your Password — Profiles CRUD Auth');
            });
        }

        // Always return same response (prevents email enumeration)
        return back()->with('success',
            'If that email exists in our system, a reset link has been sent.');
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    /**
     * Process the password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'token'    => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        // Find the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        // Check token expiry (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This reset link has expired. Please request a new one.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password reset successfully! Please log in with your new password.');
    }
}
