@extends('layouts.dashboard')
@section('title', 'Add Profile')
@section('page_title', 'Add New Profile')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition-colors">Dashboard</a>
        <span>/</span>
        <span class="text-slate-300">Add Profile</span>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
        @if($errors->any())
            <div class="mb-6 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                <p class="font-semibold mb-1">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form id="createForm" method="POST" action="{{ route('dashboard.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- Name --}}
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required minlength="2" maxlength="100"
                       placeholder="Maria Santos"
                       class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm {{ $errors->has('name') ? 'border-red-500' : '' }}">
                @error('name') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address <span class="text-red-400">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="you@example.com"
                       class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm {{ $errors->has('email') ? 'border-red-500' : '' }}">
                @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-slate-300 mb-1.5">Role <span class="text-red-400">*</span></label>
                <select id="role" name="role" required
                        class="form-input w-full bg-slate-800/60 border border-slate-700 text-white rounded-xl px-4 py-3 text-sm {{ $errors->has('role') ? 'border-red-500' : '' }}">
                    <option value="user"  {{ old('role','user') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           oninput="checkPasswordStrength(this.value,'strengthBar','strengthLabel'); checkPasswordMatch('password','password_confirmation','matchMsg')"
                           class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm {{ $errors->has('password') ? 'border-red-500' : '' }}">
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
                        <span id="strengthLabel" class="text-xs"></span>
                    </div>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-5">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm Password <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="••••••••"
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

            {{-- Phone --}}
            <div class="mb-5">
                <label for="phone" class="block text-sm font-medium text-slate-300 mb-1.5">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="+63 912 345 6789"
                       class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm">
                @error('phone') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Address --}}
            <div class="mb-5">
                <label for="address" class="block text-sm font-medium text-slate-300 mb-1.5">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}"
                       placeholder="Makati City, Metro Manila"
                       class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm">
                @error('address') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Bio --}}
            <div class="mb-5">
                <label for="bio" class="block text-sm font-medium text-slate-300 mb-1.5">Bio</label>
                <textarea id="bio" name="bio" rows="3" maxlength="500"
                          placeholder="A short description about this user…"
                          class="form-input w-full bg-slate-800/60 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm resize-none">{{ old('bio') }}</textarea>
                @error('bio') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Avatar --}}
            <div class="mb-7">
                <label for="avatar" class="block text-sm font-medium text-slate-300 mb-1.5">Profile Photo</label>
                <div class="border-2 border-dashed border-slate-700 rounded-xl p-5 text-center hover:border-indigo-500/50 transition-colors">
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    <label for="avatar" class="cursor-pointer">
                        <img id="avatarPreview" src="" alt="" class="w-16 h-16 rounded-full object-cover mx-auto mb-3 hidden ring-2 ring-indigo-500">
                        <div id="avatarPlaceholder">
                            <svg class="w-8 h-8 text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                            <p class="text-sm text-slate-400">Click to upload photo</p>
                            <p class="text-xs text-slate-600 mt-1">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </label>
                </div>
                @error('avatar') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="button" onclick="if(document.getElementById('createForm').checkValidity()) confirmAction('createForm', 'Create Profile', 'Are you sure you want to create this new profile?', 'Create Profile', 'indigo'); else document.getElementById('createForm').reportValidity();"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-6 rounded-xl transition-all text-sm shadow-lg shadow-indigo-900/30">
                    Create Profile
                </button>
                <a href="{{ route('dashboard') }}"
                   class="flex-1 text-center bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-medium py-3 px-6 rounded-xl transition-all text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('avatarPreview').src = e.target.result;
        document.getElementById('avatarPreview').classList.remove('hidden');
        document.getElementById('avatarPlaceholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
@endsection
