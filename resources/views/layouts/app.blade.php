<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Profiles CRUD Auth — Secure user management system')">
    <title>@yield('title', 'Profiles CRUD Auth')</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Input focus ring */
        .form-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.3); }
        /* Password eye button */
        .eye-btn { cursor: pointer; transition: color 0.2s; }
        .eye-btn:hover { color: #818cf8; }
        /* Strength bar animation */
        .strength-bar { transition: width 0.4s ease, background-color 0.4s ease; }
        /* Card hover */
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        /* Sidebar active */
        .nav-active { background: rgba(99,102,241,0.15); color: #818cf8; border-left: 3px solid #6366f1; }
        /* Table row hover */
        .table-row:hover { background: rgba(255,255,255,0.03); }
        /* Flash message slide in */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .flash-msg { animation: slideDown 0.3s ease forwards; }
        /* Modal backdrop */
        .modal-backdrop { backdrop-filter: blur(4px); }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased">

@yield('body')

{{-- Global JavaScript utilities --}}
<script>
/**
 * Toggle password visibility — show/hide password field.
 * @param {string} fieldId - The input field ID
 * @param {string} iconId  - The SVG icon element ID
 */
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (!field) return;

    if (field.type === 'password') {
        field.type = 'text';
        // Switch to "eye-off" SVG
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>`;
    } else {
        field.type = 'password';
        // Switch to "eye" SVG
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
    }
}

/**
 * Check password strength and update the strength meter UI.
 * @param {string} value - The current password value
 * @param {string} barId - The strength bar element ID
 * @param {string} labelId - The strength label element ID
 */
function checkPasswordStrength(value, barId, labelId) {
    const bar   = document.getElementById(barId);
    const label = document.getElementById(labelId);
    if (!bar || !label) return;

    let score = 0;
    if (value.length >= 8)                    score++;
    if (/[A-Z]/.test(value))                  score++;
    if (/[a-z]/.test(value))                  score++;
    if (/\d/.test(value))                     score++;
    if (/[\W_]/.test(value))                  score++;

    const levels = [
        { width: '0%',   color: 'bg-slate-700', text: '',         textColor: 'text-slate-500' },
        { width: '20%',  color: 'bg-red-500',   text: 'Too Weak', textColor: 'text-red-400' },
        { width: '40%',  color: 'bg-orange-500',text: 'Weak',     textColor: 'text-orange-400' },
        { width: '60%',  color: 'bg-yellow-400',text: 'Fair',     textColor: 'text-yellow-400' },
        { width: '80%',  color: 'bg-blue-400',  text: 'Good',     textColor: 'text-blue-400' },
        { width: '100%', color: 'bg-green-500', text: 'Strong',   textColor: 'text-green-400' },
    ];

    const level = levels[score];
    bar.style.width = level.width;
    bar.className = `strength-bar h-full rounded-full ${level.color}`;
    label.textContent = level.text;
    label.className = `text-xs font-medium ${level.textColor}`;
}

/**
 * Live password match checker — updates confirm field border color.
 * @param {string} passId    - The password field ID
 * @param {string} confirmId - The confirm password field ID
 * @param {string} msgId     - The match message element ID
 */
function checkPasswordMatch(passId, confirmId, msgId) {
    const pass    = document.getElementById(passId);
    const confirm = document.getElementById(confirmId);
    const msg     = document.getElementById(msgId);
    if (!pass || !confirm || !msg) return;

    if (confirm.value === '') {
        msg.textContent = '';
        confirm.classList.remove('border-green-500', 'border-red-500');
        return;
    }

    if (pass.value === confirm.value) {
        msg.textContent = '✓ Passwords match';
        msg.className   = 'text-xs text-green-400 mt-1';
        confirm.classList.remove('border-red-500');
        confirm.classList.add('border-green-500');
    } else {
        msg.textContent = '✗ Passwords do not match';
        msg.className   = 'text-xs text-red-400 mt-1';
        confirm.classList.remove('border-green-500');
        confirm.classList.add('border-red-500');
    }
}

/**
 * Auto-dismiss flash messages after 4 seconds.
 */
document.addEventListener('DOMContentLoaded', () => {
    const flashes = document.querySelectorAll('.flash-msg');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
});
</script>

@stack('scripts')

</body>
</html>
