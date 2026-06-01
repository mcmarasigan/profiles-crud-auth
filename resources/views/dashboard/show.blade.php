@extends('layouts.dashboard')
@section('title', 'Profile — ' . e($user->name))
@section('page_title', 'Profile Details')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition-colors">Dashboard</a>
        <span>/</span>
        <span class="text-slate-300">{{ e($user->name) }}</span>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        {{-- Profile Header --}}
        <div class="relative bg-gradient-to-br from-indigo-950 to-slate-900 px-8 py-10 border-b border-slate-800">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-indigo-600/5 blur-2xl"></div>
            </div>
            <div class="relative flex items-center gap-6">
                <img src="{{ $user->avatarUrl() }}" alt="{{ e($user->name) }}"
                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-indigo-500/30 shadow-xl">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ e($user->name) }}</h2>
                    <p class="text-slate-400 mb-2">{{ e($user->email) }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                 {{ $user->isAdmin() ? 'bg-indigo-900/60 text-indigo-300 border border-indigo-700/50' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Profile Details --}}
        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Phone</p>
                    <p class="text-white">{{ $user->phone ? e($user->phone) : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Address</p>
                    <p class="text-white">{{ $user->address ? e($user->address) : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Member Since</p>
                    <p class="text-white">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Last Updated</p>
                    <p class="text-white">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>

            @if($user->bio)
            <div class="mb-6">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bio</p>
                <p class="text-slate-300 text-sm leading-relaxed bg-slate-800/40 rounded-xl p-4 border border-slate-700/50">{{ e($user->bio) }}</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                @if(auth()->user()->isAdmin() || auth()->id() === $user->id)
                <a href="{{ route('dashboard.edit', $user) }}"
                   class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Edit Profile
                </a>
                @endif
                @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                <button onclick="confirmDelete({{ $user->id }}, '{{ e($user->name) }}', '{{ route('dashboard.destroy', $user) }}')"
                        class="flex items-center gap-2 border border-red-700/50 text-red-400 hover:bg-red-900/20 text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                    Delete Profile
                </button>
                @endif
                <a href="{{ route('dashboard') }}"
                   class="text-slate-400 hover:text-white text-sm px-4 py-2.5 rounded-xl hover:bg-slate-800 transition-all">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
