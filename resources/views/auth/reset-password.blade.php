@extends('layouts.app')
@section('title', 'Reset Password — Profiles CRUD Auth')
@section('body')
<div class="min-h-screen bg-slate-950 flex items-center justify-center p-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-900/40 border border-indigo-700/50 mb-4">
                <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Set new password</h1>
            <p class="text-slate-400 text-sm">Choose a strong password for your account.</p>
        </div>

        @if($errors->any())
            <div class="mb-5 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
            <form method="POST" action="{{ route('password.update') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- New Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">New password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               placeholder="••••••••"
                               autocomplete="new-password"
                               oninput="checkPasswordStrength(this.value,'strengthBar','strengthLabel'); checkPasswordMatch('password','password_confirmation','matchMsg')"
                               class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm">
                        <button type="button" onclick="togglePassword('password','passEye')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 eye-btn" tabindex="-1">
                            <svg id="passEye" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    <div class="mt-2">
                        <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div id="strengthBar" class="strength-bar h-full rounded-full bg-slate-700" style="width:0%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-slate-500">Password strength</span>
                            <span id="strengthLabel" class="text-xs text-slate-500"></span>
                        </div>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm new password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="••••••••"
                               autocomplete="new-password"
                               oninput="checkPasswordMatch('password','password_confirmation','matchMsg')"
                               class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm">
                        <button type="button" onclick="togglePassword('password_confirmation','confirmEye')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 eye-btn" tabindex="-1">
                            <svg id="confirmEye" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    <p id="matchMsg" class="text-xs mt-1"></p>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-4 rounded-xl transition-all text-sm">
                    Reset Password
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-sm mt-6">
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300">← Back to Sign In</a>
        </p>
    </div>
</div>
@endsection
