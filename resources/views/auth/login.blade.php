@extends('layouts.app')

@section('title', 'Sign In — Profiles CRUD Auth')
@section('meta_description', 'Sign in to your Profiles CRUD Auth account')

@section('body')
<div class="min-h-screen bg-slate-950 flex">
    {{-- Left: Decorative Panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 p-12 flex-col justify-between overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-purple-600/10 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-indigo-500/5 blur-2xl"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-16">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-900">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">Profiles Auth</span>
            </div>

            <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                Manage profiles<br>
                <span class="text-indigo-400">securely.</span>
            </h2>
            <p class="text-slate-400 text-lg leading-relaxed">
                A modern, secure user management system built with PHP 8, Laravel & Tailwind CSS.
            </p>
        </div>

        {{-- Feature list --}}
        <div class="relative z-10 space-y-3">
            @foreach([
                'Secure authentication with CSRF protection',
                'Password hashing & session management',
                'Full CRUD with role-based access control',
            ] as $text)
            <div class="flex items-center gap-3 text-slate-400 text-sm">
                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $text }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Login Form --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white">Profiles Auth</span>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-2">Welcome back</h1>
            <p class="text-slate-400 mb-8">Sign in to your account to continue</p>

            {{-- Flash success --}}
            @if(session('success'))
                <div class="flash-msg mb-5 flex items-center gap-2 bg-green-900/30 border border-green-700/50 text-green-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-5 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="you@example.com"
                           class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                  rounded-xl px-4 py-3 text-sm
                                  {{ $errors->has('email') ? 'border-red-500' : '' }}">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <p id="emailError" class="text-red-400 text-xs mt-1.5 hidden">Please enter a valid email address.</p>
                </div>

                {{-- Password with eye toggle --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                        <a href="{{ route('password.forgot') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                      rounded-xl px-4 py-3 pr-12 text-sm
                                      {{ $errors->has('password') ? 'border-red-500' : '' }}">
                        {{-- Eye toggle button --}}
                        <button type="button"
                                onclick="togglePassword('password', 'passwordEyeIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 eye-btn"
                                aria-label="Toggle password visibility"
                                tabindex="-1">
                            <svg id="passwordEyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <p id="passwordError" class="text-red-400 text-xs mt-1.5 hidden">Password is required.</p>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="text-sm text-slate-400">Remember me for 30 days</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        id="loginSubmit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold
                               py-3 px-4 rounded-xl transition-all duration-200 text-sm shadow-lg shadow-indigo-900/40
                               disabled:opacity-60 disabled:cursor-not-allowed">
                    Sign In
                </button>
            </form>

            <p class="text-center text-slate-400 text-sm mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                    Create one free
                </a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
/**
 * Client-side validation for the login form.
 * Prevents submission with empty/invalid fields.
 */
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let valid = true;

    const email    = document.getElementById('email');
    const password = document.getElementById('password');
    const emailErr = document.getElementById('emailError');
    const passErr  = document.getElementById('passwordError');

    // Reset errors
    emailErr.classList.add('hidden');
    passErr.classList.add('hidden');
    email.classList.remove('border-red-500');
    password.classList.remove('border-red-500');

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
        emailErr.classList.remove('hidden');
        email.classList.add('border-red-500');
        valid = false;
    }

    // Validate password
    if (!password.value.trim()) {
        passErr.classList.remove('hidden');
        password.classList.add('border-red-500');
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
        return;
    }

    // Show loading state
    const btn = document.getElementById('loginSubmit');
    btn.disabled = true;
    btn.textContent = 'Signing in…';
});

// Live email format validation
document.getElementById('email').addEventListener('input', function() {
    const emailErr = document.getElementById('emailError');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailRegex.test(this.value)) {
        emailErr.classList.remove('hidden');
        this.classList.add('border-red-500');
    } else {
        emailErr.classList.add('hidden');
        this.classList.remove('border-red-500');
    }
});

// Auto-dismiss flash
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-msg').forEach(el => {
        setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 4000);
    });
});
</script>
@endpush
@endsection
