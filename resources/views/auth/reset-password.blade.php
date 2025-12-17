@extends('layouts.app')

@section('body-class', 'bg-[#38B7F7]')

@push('styles')
<style>
    body {
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: auto !important;
        min-height: 100vh !important;
    }

    body.bg-\[\#38B7F7\] {
        position: relative;
        background: #A5DFF9 url('{{ asset("img/BCO.655ce4cc-cb22-4b9e-ae5d-867a0e717d60.png") }}') center/cover no-repeat;
        min-height: 100vh;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    body.bg-\[\#38B7F7\]:before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(56, 183, 247, 0.45);
        z-index: 0;
    }

    .sidebar, #mobile-sidebar-toggle {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="relative flex min-h-screen w-full items-center justify-center px-4 py-8">
    <div class="relative z-10 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/70 backdrop-blur-sm text-blue-900 shadow-2xl shadow-blue-900/20">

            <!-- Logo and Header -->
            <div class="px-8 pt-8 pb-6 text-center">
                <img
                    src="{{ asset('img/NVG_LOGO_org.png') }}"
                    alt="NVG Prime Movers logo"
                    class="mx-auto mb-4 h-20 w-auto drop-shadow-lg" />
                <h2 class="text-3xl font-bold tracking-tight text-blue-900">
                    Reset Password
                </h2>
                <p class="mt-2 text-sm text-blue-600 font-medium">
                    Create a new secure password
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-6 px-8 pb-8">

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-blue-900">
                            Email Address:
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.5a2.25 2.25 0 01-2.26 0l-7.5-4.5a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                autofocus
                                class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 py-3 pl-11 pr-4 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('email') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                placeholder="your@email.com"
                                value="{{ old('email', $email) }}">
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- New Password Field -->
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-blue-900">
                            New Password:
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600 z-10">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V12.75a2.25 2.25 0 012.25-2.25z" />
                                </svg>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                oninput="checkPasswordStrength()"
                                class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 py-3 pl-11 pr-11 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('password') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                placeholder="Enter new password">
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password')"
                                class="absolute inset-y-0 right-0 flex h-full items-center pr-3 text-blue-600 hover:text-blue-800 z-10">
                                <svg id="password-eye" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div id="password-strength" class="mt-2 hidden">
                            <div class="flex gap-1 mb-2">
                                <div id="strength-bar-1" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                <div id="strength-bar-2" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                <div id="strength-bar-3" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                <div id="strength-bar-4" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                            </div>
                            <p id="strength-text" class="text-xs font-medium text-gray-600"></p>
                        </div>

                        <!-- Password Requirements -->
                        <div class="mt-2 space-y-1 text-xs">
                            <p class="font-semibold text-blue-900">Password must contain:</p>
                            <div class="grid grid-cols-2 gap-1">
                                <div id="req-length" class="flex items-center gap-1 text-gray-600">
                                    <span class="text-gray-400">?</span> 8+ characters
                                </div>
                                <div id="req-uppercase" class="flex items-center gap-1 text-gray-600">
                                    <span class="text-gray-400">?</span> Uppercase (A-Z)
                                </div>
                                <div id="req-lowercase" class="flex items-center gap-1 text-gray-600">
                                    <span class="text-gray-400">?</span> Lowercase (a-z)
                                </div>
                                <div id="req-number" class="flex items-center gap-1 text-gray-600">
                                    <span class="text-gray-400">?</span> Number (0-9)
                                </div>
                                <div id="req-special" class="flex items-center gap-1 text-gray-600 col-span-2">
                                    <span class="text-gray-400">?</span> Special character (@$!%*?&#)
                                </div>
                            </div>
                        </div>

                        @error('password')
                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-blue-900">
                            Confirm Password:
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600 z-10">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 py-3 pl-11 pr-11 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70"
                                placeholder="Confirm new password">
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password_confirmation')"
                                class="absolute inset-y-0 right-0 flex h-full items-center pr-3 text-blue-600 hover:text-blue-800 z-10">
                                <svg id="password_confirmation-eye" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                            <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            <span class="relative">Reset Password</span>
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <p class="text-center text-sm text-blue-800">
                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 transition hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Back to Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
    } else {
        field.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');
    
    if (password.length === 0) {
        strengthDiv.classList.add('hidden');
        return;
    }
    
    strengthDiv.classList.remove('hidden');
    
    let strength = 0;
    const checks = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[@$!%*?&#]/.test(password)
    };
    
    // Update requirement checkmarks
    updateRequirement('req-length', checks.length);
    updateRequirement('req-uppercase', checks.uppercase);
    updateRequirement('req-lowercase', checks.lowercase);
    updateRequirement('req-number', checks.number);
    updateRequirement('req-special', checks.special);
    
    // Calculate strength
    if (checks.length) strength++;
    if (checks.uppercase) strength++;
    if (checks.lowercase) strength++;
    if (checks.number) strength++;
    if (checks.special) strength++;
    
    // Update strength bars
    const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
    bars.forEach((bar, index) => {
        const element = document.getElementById(bar);
        element.classList.remove('bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500');
        
        if (index < strength) {
            if (strength <= 2) element.classList.add('bg-red-500');
            else if (strength === 3) element.classList.add('bg-orange-500');
            else if (strength === 4) element.classList.add('bg-yellow-500');
            else element.classList.add('bg-green-500');
        } else {
            element.classList.add('bg-gray-200');
        }
    });
    
    // Update text
    if (strength <= 2) {
        strengthText.textContent = 'Weak password';
        strengthText.className = 'text-xs font-medium text-red-600';
    } else if (strength === 3) {
        strengthText.textContent = 'Fair password';
        strengthText.className = 'text-xs font-medium text-orange-600';
    } else if (strength === 4) {
        strengthText.textContent = 'Good password';
        strengthText.className = 'text-xs font-medium text-yellow-600';
    } else {
        strengthText.textContent = 'Strong password!';
        strengthText.className = 'text-xs font-medium text-green-600';
    }
}

function updateRequirement(id, met) {
    const element = document.getElementById(id);
    if (met) {
        element.classList.remove('text-gray-600');
        element.classList.add('text-green-600', 'font-semibold');
        element.querySelector('span').textContent = '?';
        element.querySelector('span').classList.remove('text-gray-400');
        element.querySelector('span').classList.add('text-green-600');
    } else {
        element.classList.remove('text-green-600', 'font-semibold');
        element.classList.add('text-gray-600');
        element.querySelector('span').textContent = '?';
        element.querySelector('span').classList.remove('text-green-600');
        element.querySelector('span').classList.add('text-gray-400');
    }
}
</script>
@endsection
