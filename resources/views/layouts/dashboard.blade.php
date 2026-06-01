{{-- Dashboard layout with sidebar navigation --}}
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Profiles CRUD Auth Dashboard')">
    <title>@yield('title', 'Dashboard') — Profiles CRUD Auth</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .form-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.3); }
        .eye-btn:hover { color: #818cf8; }
        .strength-bar { transition: width 0.4s ease, background-color 0.4s ease; }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .nav-active { background: rgba(99,102,241,0.15) !important; color: #818cf8 !important; border-left: 3px solid #6366f1; }
        .table-row:hover td { background: rgba(255,255,255,0.02); }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .flash-msg { animation: slideDown 0.3s ease forwards; }
        .modal-backdrop { backdrop-filter: blur(4px); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 3px; }
        .sidebar-link { transition: all 0.15s ease; border-left: 3px solid transparent; }
        .sidebar-link:hover { background: rgba(99,102,241,0.08); color: #c7d2fe; border-left-color: rgba(99,102,241,0.4); }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased flex">

{{-- ===================== SIDEBAR ===================== --}}
<aside class="w-64 min-h-screen bg-slate-900 border-r border-slate-800 flex flex-col fixed top-0 left-0 z-30">
    {{-- Logo --}}
    <div class="p-6 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-900/50">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-white leading-tight">Profiles Auth</p>
                <p class="text-xs text-slate-500">Management System</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 p-4 space-y-1">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Main Menu</p>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300
                  {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('dashboard.create') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300
                  {{ request()->routeIs('dashboard.create') ? 'nav-active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
            </svg>
            Add Profile
        </a>
        @endif

        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-5 mb-3 px-3">Account</p>

        <a href="{{ route('dashboard.edit', auth()->user()) }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
            </svg>
            Edit My Profile
        </a>
    </nav>

    {{-- User Info + Logout --}}
    <div class="p-4 border-t border-slate-800">
        <div class="flex items-center gap-3 mb-3">
            <img src="{{ auth()->user()->avatarUrl() }}"
                 alt="{{ e(auth()->user()->name) }}"
                 class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-500/30">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ e(auth()->user()->name) }}</p>
                <span class="text-xs px-1.5 py-0.5 rounded-full {{ auth()->user()->isAdmin() ? 'bg-indigo-900/60 text-indigo-300' : 'bg-slate-800 text-slate-400' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmAction('logoutForm', 'Sign Out', 'Are you sure you want to sign out of your account?', 'Sign Out', 'red')"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-red-400 hover:bg-red-900/20 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ===================== MAIN CONTENT ===================== --}}
<div class="flex-1 ml-64 flex flex-col min-h-screen">

    {{-- Top Bar --}}
    <header class="sticky top-0 z-20 bg-slate-950/80 backdrop-blur-md border-b border-slate-800 px-8 py-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-white">@yield('page_title', 'Dashboard')</h1>
        <div class="flex items-center gap-3 text-sm text-slate-400">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ now()->format('M d, Y') }}
        </div>
    </header>

    {{-- Flash Messages --}}
    <div class="px-8 pt-6 space-y-3">
        @if(session('success'))
            <div class="flash-msg flex items-center gap-3 bg-green-900/30 border border-green-700/50 text-green-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-msg flex items-center gap-3 bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="flex-1 px-8 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-800 px-8 py-4 text-center text-xs text-slate-600">
        Profiles CRUD Auth &copy; {{ date('Y') }} — Built with Laravel {{ app()->version() }} &amp; Tailwind CSS
    </footer>
</div>

{{-- Delete Confirmation Modal (Global) --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop bg-black/70">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-900/30 border border-red-700/50 mb-5 mx-auto">
            <svg class="w-7 h-7 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white text-center mb-2">Confirm Delete</h3>
        <p class="text-slate-400 text-center text-sm mb-6">Are you sure you want to delete <strong id="deleteUserName" class="text-white"></strong>? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 font-medium text-sm transition-colors">
                Cancel
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-medium text-sm transition-colors">
                    Delete Profile
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Generic Action Confirmation Modal --}}
<div id="genericConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop bg-black/70">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
        <div id="confirmIconContainer" class="flex items-center justify-center w-14 h-14 rounded-full mb-5 mx-auto">
            <svg id="confirmIcon" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
            </svg>
        </div>
        <h3 id="confirmModalTitle" class="text-xl font-bold text-white text-center mb-2">Confirm Action</h3>
        <p id="confirmModalMessage" class="text-slate-400 text-center text-sm mb-6">Are you sure?</p>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 font-medium text-sm transition-colors">
                Cancel
            </button>
            <button id="confirmSubmitBtn" onclick="executeConfirmAction()"
                    class="flex-1 px-4 py-2.5 rounded-xl text-white font-medium text-sm transition-colors">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
// Flash message auto-dismiss
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-msg').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
});

// Delete confirmation modal
function confirmDelete(userId, userName, deleteUrl) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals on backdrop click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.getElementById('genericConfirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmModal();
});

// Generic Confirmation Modal Logic
let confirmFormId = null;
function confirmAction(formId, title, message, btnText, color) {
    confirmFormId = formId;
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalMessage').textContent = message;
    
    const btn = document.getElementById('confirmSubmitBtn');
    btn.textContent = btnText;
    btn.className = `flex-1 px-4 py-2.5 rounded-xl text-white font-medium text-sm transition-colors bg-${color}-600 hover:bg-${color}-500`;
    
    const iconContainer = document.getElementById('confirmIconContainer');
    iconContainer.className = `flex items-center justify-center w-14 h-14 rounded-full mb-5 mx-auto bg-${color}-900/30 border border-${color}-700/50`;
    
    const icon = document.getElementById('confirmIcon');
    icon.className = `w-7 h-7 text-${color}-400`;
    
    document.getElementById('genericConfirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('genericConfirmModal').classList.add('hidden');
    confirmFormId = null;
}

function executeConfirmAction() {
    if (confirmFormId) {
        document.getElementById(confirmFormId).submit();
    }
}

// Password utilities (reused across dashboard forms)
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (!field) return;
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>`;
    } else {
        field.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
    }
}

function checkPasswordStrength(value, barId, labelId) {
    const bar = document.getElementById(barId);
    const label = document.getElementById(labelId);
    if (!bar || !label) return;
    let score = 0;
    if (value.length >= 8) score++;
    if (/[A-Z]/.test(value)) score++;
    if (/[a-z]/.test(value)) score++;
    if (/\d/.test(value)) score++;
    if (/[\W_]/.test(value)) score++;
    const levels = [
        { width: '0%',   color: 'bg-slate-700', text: '',         textColor: 'text-slate-500' },
        { width: '20%',  color: 'bg-red-500',   text: 'Too Weak', textColor: 'text-red-400' },
        { width: '40%',  color: 'bg-orange-500',text: 'Weak',     textColor: 'text-orange-400' },
        { width: '60%',  color: 'bg-yellow-400',text: 'Fair',     textColor: 'text-yellow-400' },
        { width: '80%',  color: 'bg-blue-400',  text: 'Good',     textColor: 'text-blue-400' },
        { width: '100%', color: 'bg-green-500', text: 'Strong ✓', textColor: 'text-green-400' },
    ];
    const level = levels[score];
    bar.style.width = level.width;
    bar.className = `strength-bar h-full rounded-full ${level.color}`;
    label.textContent = level.text;
    label.className = `text-xs font-medium ${level.textColor}`;
}

function checkPasswordMatch(passId, confirmId, msgId) {
    const pass = document.getElementById(passId);
    const confirm = document.getElementById(confirmId);
    const msg = document.getElementById(msgId);
    if (!pass || !confirm || !msg) return;
    if (confirm.value === '') { msg.textContent = ''; confirm.classList.remove('border-green-500','border-red-500'); return; }
    if (pass.value === confirm.value) {
        msg.textContent = '✓ Passwords match'; msg.className = 'text-xs text-green-400 mt-1';
        confirm.classList.remove('border-red-500'); confirm.classList.add('border-green-500');
    } else {
        msg.textContent = '✗ Passwords do not match'; msg.className = 'text-xs text-red-400 mt-1';
        confirm.classList.remove('border-green-500'); confirm.classList.add('border-red-500');
    }
}
</script>

@stack('scripts')
</body>
</html>
