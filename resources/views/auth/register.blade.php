@extends('layouts.app')

@section('title', 'Create Account — Profiles CRUD Auth')
@section('meta_description', 'Register a new Profiles CRUD Auth account')

@section('body')
<div class="min-h-screen bg-slate-950 flex">
    {{-- Left: Decorative --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 p-12 flex-col justify-between overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-purple-600/10 blur-3xl"></div>

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
                Join today.<br>
                <span class="text-indigo-400">It's free.</span>
            </h2>
            <p class="text-slate-400 text-lg leading-relaxed">
                Create your account and start managing profiles with enterprise-grade security.
            </p>
        </div>

        {{-- Password requirements hint --}}
        <div class="relative z-10 bg-slate-800/50 rounded-2xl p-5 border border-slate-700/50">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Password Requirements</p>
            <div class="space-y-2 text-sm text-slate-400">
                <div class="flex items-center gap-2"><span class="text-indigo-400">→</span> At least 8 characters</div>
                <div class="flex items-center gap-2"><span class="text-indigo-400">→</span> Uppercase &amp; lowercase letters</div>
                <div class="flex items-center gap-2"><span class="text-indigo-400">→</span> At least one number</div>
                <div class="flex items-center gap-2"><span class="text-indigo-400">→</span> At least one special character (!@#$...)</div>
            </div>
        </div>
    </div>

    {{-- Right: Register Form --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white">Profiles Auth</span>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-2">Create account</h1>
            <p class="text-slate-400 mb-8">Fill in your details to get started</p>

            {{-- Server-side errors --}}
            @if($errors->any())
                <div class="mb-5 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <p class="font-semibold mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('register.post') }}" novalidate>
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Full name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           minlength="2"
                           maxlength="100"
                           autocomplete="name"
                           placeholder="Maria Santos"
                           class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                  rounded-xl px-4 py-3 text-sm {{ $errors->has('name') ? 'border-red-500' : '' }}">
                    @error('name') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    <p id="nameError" class="text-red-400 text-xs mt-1.5 hidden">Name must be at least 2 characters.</p>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="you@example.com"
                           class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                  rounded-xl px-4 py-3 text-sm {{ $errors->has('email') ? 'border-red-500' : '' }}">
                    @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    <p id="emailError" class="text-red-400 text-xs mt-1.5 hidden">Please enter a valid email address.</p>
                </div>

                {{-- Password with eye toggle + strength meter --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               oninput="checkPasswordStrength(this.value, 'strengthBar', 'strengthLabel'); checkPasswordMatch('password','password_confirmation','matchMsg')"
                               class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                      rounded-xl px-4 py-3 pr-12 text-sm {{ $errors->has('password') ? 'border-red-500' : '' }}">
                        <button type="button"
                                onclick="togglePassword('password', 'passEyeIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 eye-btn"
                                tabindex="-1" aria-label="Toggle password visibility">
                            <svg id="passEyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror

                    {{-- Password strength meter --}}
                    <div class="mt-2">
                        <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div id="strengthBar" class="strength-bar h-full rounded-full bg-slate-700" style="width:0%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-slate-500">Password strength</span>
                            <span id="strengthLabel" class="text-xs text-slate-500"></span>
                        </div>
                    </div>
                </div>

                {{-- Confirm Password with eye toggle + match indicator --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm password</label>
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               oninput="checkPasswordMatch('password','password_confirmation','matchMsg')"
                               class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                                      rounded-xl px-4 py-3 pr-12 text-sm">
                        <button type="button"
                                onclick="togglePassword('password_confirmation', 'confirmEyeIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 eye-btn"
                                tabindex="-1" aria-label="Toggle confirm password visibility">
                            <svg id="confirmEyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    <p id="matchMsg" class="text-xs mt-1"></p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        id="registerSubmit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold
                               py-3 px-4 rounded-xl transition-all duration-200 text-sm shadow-lg shadow-indigo-900/40
                               disabled:opacity-60 disabled:cursor-not-allowed">
                    Create Account
                </button>
            </form>

            <p class="text-center text-slate-400 text-sm mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
/**
 * Client-side validation for the registration form.
 */
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    const name     = document.getElementById('name');
    const email    = document.getElementById('email');
    const password = document.getElementById('password');
    const confirm  = document.getElementById('password_confirmation');

    // Reset
    ['nameError','emailError'].forEach(id => document.getElementById(id).classList.add('hidden'));
    [name, email, password, confirm].forEach(f => f.classList.remove('border-red-500'));

    // Name
    if (!name.value.trim() || name.value.trim().length < 2) {
        document.getElementById('nameError').classList.remove('hidden');
        name.classList.add('border-red-500');
        valid = false;
    }

    // Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim() || !emailRegex.test(email.value)) {
        document.getElementById('emailError').classList.remove('hidden');
        email.classList.add('border-red-500');
        valid = false;
    }

    // Password strength
    const pass = password.value;
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passRegex.test(pass)) {
        password.classList.add('border-red-500');
        valid = false;
        alert('Password must be at least 8 characters and include uppercase, lowercase, number, and special character.');
    }

    // Match
    if (password.value !== confirm.value) {
        confirm.classList.add('border-red-500');
        valid = false;
    }

    if (!valid) { e.preventDefault(); return; }

    const btn = document.getElementById('registerSubmit');
    btn.disabled = true;
    btn.textContent = 'Creating account…';
});
</script>
@endpush
@endsection
