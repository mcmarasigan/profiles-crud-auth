@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('page_title', 'User Profiles')
@section('meta_description', 'Manage all user profiles in the system')

@section('content')

@if(auth()->user()->isAdmin())
{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    @php
        $totalUsers = \App\Models\User::count();
        $adminCount = \App\Models\User::where('role','admin')->count();
        $userCount  = \App\Models\User::where('role','user')->count();
    @endphp
    @foreach([
        ['label'=>'Total Profiles', 'value'=>$totalUsers, 'icon'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z', 'color'=>'indigo'],
        ['label'=>'Administrators', 'value'=>$adminCount, 'icon'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'color'=>'purple'],
        ['label'=>'Regular Users',  'value'=>$userCount,  'icon'=>'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z', 'color'=>'blue'],
    ] as $stat)
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 card-hover">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-slate-400">{{ $stat['label'] }}</p>
            <div class="w-9 h-9 rounded-xl bg-{{ $stat['color'] }}-900/40 border border-{{ $stat['color'] }}-700/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-{{ $stat['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-white">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Search & Add --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 flex-1 max-w-sm">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or email…"
                   class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500
                          rounded-xl pl-10 pr-4 py-2.5 text-sm">
        </div>
        <button type="submit" class="px-4 py-2.5 bg-slate-800 border border-slate-700 text-slate-300 hover:text-white rounded-xl text-sm transition-colors">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('dashboard') }}" class="px-3 py-2.5 text-slate-400 hover:text-white text-sm transition-colors">Clear</a>
        @endif
    </form>

    <a href="{{ route('dashboard.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold
              px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-900/30">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Add Profile
    </a>
</div>
@endif

@if(auth()->user()->isAdmin())
{{-- Profiles Table (Admin Only) --}}
<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
    @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <p class="text-slate-400 font-medium">No profiles found</p>
            <p class="text-slate-600 text-sm mt-1">
                @if(request('search'))
                    No results for "{{ e(request('search')) }}"
                @else
                    Start by adding a new profile
                @endif
            </p>
        </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-slate-800/60 border-b border-slate-700">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">User</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">Email</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Phone</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Role</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Joined</th>
                <th class="text-right px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($users as $user)
            <tr class="table-row">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatarUrl() }}"
                             alt="{{ e($user->name) }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-2 ring-slate-700">
                        <div class="min-w-0">
                            <a href="{{ route('dashboard.show', $user) }}"
                               class="text-white font-medium hover:text-indigo-400 transition-colors truncate block">
                                {{ e($user->name) }}
                            </a>
                            <p class="text-slate-500 text-xs truncate md:hidden">{{ e($user->email) }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-400 hidden md:table-cell">{{ e($user->email) }}</td>
                <td class="px-6 py-4 text-slate-400 hidden lg:table-cell">{{ e($user->phone ?? '—') }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                 {{ $user->isAdmin() ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-700/50' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-slate-500 text-xs hidden sm:table-cell">
                    {{ $user->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('dashboard.show', $user) }}"
                           class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 transition-all"
                           title="View profile">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->id() === $user->id)
                        <a href="{{ route('dashboard.edit', $user) }}"
                           class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-400 hover:bg-indigo-900/30 transition-all"
                           title="Edit profile">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                        </a>
                        @endif
                        @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                        <button onclick="confirmDelete({{ $user->id }}, '{{ e($user->name) }}', '{{ route('dashboard.destroy', $user) }}')"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-900/20 transition-all"
                                title="Delete profile">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-800">
        {{ $users->links('pagination::tailwind') }}
    </div>
    @endif
    @endif
</div>
@else
{{-- Welcome Dashboard (Regular Users) --}}
<div class="bg-slate-900 border border-slate-800 rounded-2xl p-10 flex flex-col items-center justify-center text-center shadow-xl">
    <div class="relative mb-6">
        <div class="absolute inset-0 bg-indigo-500 blur-[30px] opacity-20 rounded-full"></div>
        <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar" class="relative w-32 h-32 rounded-full ring-4 ring-slate-800 object-cover shadow-2xl">
    </div>
    <h2 class="text-3xl font-bold text-white mb-3">Welcome back, {{ auth()->user()->name }}! 👋</h2>
    <p class="text-slate-400 max-w-md mx-auto mb-10 leading-relaxed">
        Manage your personal information, update your profile picture, and configure your account settings securely from your dashboard.
    </p>
    
    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
        <a href="{{ route('dashboard.show', auth()->user()) }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-indigo-900/30 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            View My Profile
        </a>
        <a href="{{ route('dashboard.edit', auth()->user()) }}" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 font-medium px-8 py-3.5 rounded-xl transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
            </svg>
            Edit Details
        </a>
    </div>
</div>
@endif
@endsection
