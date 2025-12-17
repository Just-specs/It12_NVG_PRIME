@extends('layouts.app')

@section('body-class', 'bg-[#38B7F7]')

@push('styles')
<style>
    /* Override body flex from layout */
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

    /* Hide sidebar and main-content wrapper on register page */
    .sidebar,
    #mobile-sidebar-toggle {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="relative flex min-h-screen w-full items-center justify-center px-4 py-6">
    <div class="relative z-10 w-full max-w-lg">
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/70 backdrop-blur-sm text-blue-900 shadow-2xl shadow-blue-900/20">
            
            <!-- Logo and Header -->
            <div class="px-6 pt-6 pb-4 text-center">
                <img
                    src="{{ asset('img/NVG_LOGO_org.png') }}"
                    alt="RVG Prime Movers logo"
                    class="mx-auto mb-3 h-16 w-auto drop-shadow-lg" />
                <h2 class="text-2xl font-bold tracking-tight text-blue-900">
                    Create Account
                </h2>
                <p class="mt-1 text-xs text-blue-600 font-medium">
                    NVG Prime Movers - Dispatch Management
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-4 px-6 pb-6">
                
                @if ($errors->any())
                <div class="flex items-start gap-2 rounded-xl border border-rose-500/30 bg-rose-50 p-3 text-left">
                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                        <p class="text-xs font-medium text-rose-700">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div class="flex items-start gap-2 rounded-xl border border-rose-500/30 bg-rose-50 p-3 text-left">
                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Google Sign Up Button -->
                <div>
                    <a href="{{ route('google.register') }}" class="group relative inline-flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-white border-2 border-blue-300 px-4 py-2.5 text-sm font-semibold text-blue-900 shadow-lg shadow-blue-900/15 transition hover:scale-[1.02] hover:border-blue-400 hover:shadow-xl hover:shadow-blue-900/20 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                        <svg class="h-4 w-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span class="relative">Sign up with Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-blue-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-white/70 px-3 text-blue-600 font-medium">OR</span>
                    </div>
                </div>

                <!-- Traditional Registration Form -->
                <form class="space-y-3" action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="space-y-3">
                        <!-- Full Name Field -->
                        <div>
                            <label for="name" class="mb-1.5 block text-xs font-semibold text-blue-900">
                                Full Name
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    autocomplete="name"
                                    required
                                    class="w-full rounded-xl border-2 border-blue-300 bg-blue-50 py-2.5 pl-10 pr-3 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('name') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                    placeholder="Enter your full name"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-semibold text-blue-900">
                                Email Address
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.5a2.25 2.25 0 01-2.26 0l-7.5-4.5a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="w-full rounded-xl border-2 border-blue-300 bg-blue-50 py-2.5 pl-10 pr-3 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('email') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                    placeholder="your@email.com"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-semibold text-blue-900">
                                Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600 z-10">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V12.75a2.25 2.25 0 012.25-2.25z" />
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    oninput="checkPasswordStrength()"
                                    class="w-full rounded-xl border-2 border-blue-300 bg-blue-50 py-2.5 pl-10 pr-10 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('password') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                    placeholder="Create password">
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('password')"
                                    class="absolute inset-y-0 right-0 flex h-full items-center pr-3 text-blue-600 hover:text-blue-800 z-10">
                                    <svg id="password-eye" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div id="password-strength" class="mt-2 hidden">
                                <div class="flex gap-1 mb-1.5">
                                    <div id="strength-bar-1" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                    <div id="strength-bar-2" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                    <div id="strength-bar-3" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                    <div id="strength-bar-4" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                                </div>
                                <p id="strength-text" class="text-xs font-medium text-gray-600"></p>
                            </div>

                            <!-- Password Requirements -->
                            <div class="mt-1.5 text-[10px] leading-tight">
                                <div class="grid grid-cols-2 gap-x-2 gap-y-0.5">
                                    <div id="req-length" class="flex items-center gap-1 text-gray-500">
                                        <span class="text-gray-400">?</span> 8+ chars
                                    </div>
                                    <div id="req-uppercase" class="flex items-center gap-1 text-gray-500">
                                        <span class="text-gray-400">?</span> A-Z
                                    </div>
                                    <div id="req-lowercase" class="flex items-center gap-1 text-gray-500">
                                        <span class="text-gray-400">?</span> a-z
                                    </div>
                                    <div id="req-number" class="flex items-center gap-1 text-gray-500">
                                        <span class="text-gray-400">?</span> 0-9
                                    </div>
                                    <div id="req-special" class="flex items-center gap-1 text-gray-500 col-span-2">
                                        <span class="text-gray-400">?</span> Special (@$!%*?&#)
                                    </div>
                                </div>
                            </div>

                            @error('password')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-blue-900">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600 z-10">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="w-full rounded-xl border-2 border-blue-300 bg-blue-50 py-2.5 pl-10 pr-10 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70"
                                    placeholder="Confirm password">
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('password_confirmation')"
                                    class="absolute inset-y-0 right-0 flex h-full items-center pr-3 text-blue-600 hover:text-blue-800 z-10">
                                    <svg id="password_confirmation-eye" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1">
                        <button
                            type="submit"
                            class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                            <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                            <span class="relative">Create Account</span>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <p class="text-center text-xs text-blue-800">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 transition hover:text-blue-800 hover:underline">
                            Sign in here
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
        element.classList.remove('text-gray-500');
        element.classList.add('text-green-600', 'font-semibold');
        element.querySelector('span').textContent = '?';
        element.querySelector('span').classList.remove('text-gray-400');
        element.querySelector('span').classList.add('text-green-600');
    } else {
        element.classList.remove('text-green-600', 'font-semibold');
        element.classList.add('text-gray-500');
        element.querySelector('span').textContent = '?';
        element.querySelector('span').classList.remove('text-green-600');
        element.querySelector('span').classList.add('text-gray-400');
    }
}
</script>
@endsection


