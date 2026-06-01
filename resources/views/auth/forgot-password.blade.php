@extends('layouts.app')
@section('title', 'Forgot Password — Profiles CRUD Auth')
@section('body')
<div class="min-h-screen bg-slate-950 flex items-center justify-center p-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-900/40 border border-indigo-700/50 mb-4">
                <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Forgot password?</h1>
            <p class="text-slate-400 text-sm">Enter your email and we'll send you a reset link.</p>
        </div>

        @if(session('success'))
            <div class="flash-msg mb-5 flex items-center gap-2 bg-green-900/30 border border-green-700/50 text-green-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="you@example.com"
                           class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm">
                    @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-4 rounded-xl transition-all text-sm">
                    Send Reset Link
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-sm mt-6">
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">← Back to Sign In</a>
        </p>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-msg').forEach(el => {
        setTimeout(() => { el.style.opacity='0'; setTimeout(()=>el.remove(),500); }, 5000);
    });
});
</script>
@endpush
@endsection
