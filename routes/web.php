<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| All routes use CSRF protection automatically via Laravel's web middleware.
| Auth routes redirect authenticated users; dashboard routes require login.
*/

// --- Redirect root to dashboard or login ---
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// --- Guest-only routes (redirect if already logged in) ---
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Forgot Password (BONUS)
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

    // Reset Password (BONUS)
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// --- Authenticated routes (require login) ---
Route::middleware('auth')->group(function () {

    // Logout (POST only for CSRF safety)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard + Profile CRUD
    Route::prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('');
        Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('.show');
        Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('.edit');
        Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('.update');
    });

    // Admin-only CRUD routes
    Route::middleware('admin')->prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/create', [ProfileController::class, 'create'])->name('.create');
        Route::post('/create', [ProfileController::class, 'store'])->name('.store');
        Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('.destroy');
    });
});
