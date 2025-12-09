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

    /* Hide sidebar and main-content wrapper on login page */
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
<div class="relative flex min-h-screen w-full items-center justify-center px-4 py-8">
    <div class="relative z-10 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/70 backdrop-blur-sm text-blue-900 shadow-2xl shadow-blue-900/20">

            <!-- Logo and Header -->
            <div class="px-8 pt-8 pb-6 text-center">
                <img
                    src="{{ asset('Img/NVG_LOGO org.png') }}"
                    alt="RVG Prime Movers logo"
                    class="mx-auto mb-4 h-20 w-auto drop-shadow-lg" />
                <h2 class="text-3xl font-bold tracking-tight text-blue-900">
                    NVG PRIME MOVERS
                </h2>
                <p class="mt-2 text-sm text-blue-600 font-medium">
                    Dispatch Management System
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-6 px-8 pb-8">

                <!-- Google Sign In Button -->
                <div>
                    <a href="{{ route('google.login') }}" class="group relative inline-flex w-full items-center justify-center gap-3 overflow-hidden rounded-2xl bg-white border-2 border-blue-300 px-4 py-3 text-sm font-semibold text-blue-900 shadow-lg shadow-blue-900/15 transition hover:scale-[1.02] hover:border-blue-400 hover:shadow-xl hover:shadow-blue-900/20 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                        <svg class="h-5 w-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span class="relative">Sign in with Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t-2 border-blue-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white/70 px-4 text-blue-600 font-medium">OR</span>
                    </div>
                </div>

                <!-- Traditional Login Form -->
                <form class="space-y-3" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
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
                                    autocomplete="email"
                                    required
                                    class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 py-3 pl-11 pr-4 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('email') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                    placeholder="Enter your email"
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-blue-900">
                                Password:
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex h-full items-center pl-3 text-blue-600">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V12.75a2.25 2.25 0 012.25-2.25z" />
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 py-3 pl-11 pr-4 text-sm text-blue-950 caret-blue-700 placeholder-blue-400 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('password') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                    placeholder="Enter your password">
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-blue-700 cursor-pointer">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                            <span>Remember me</span>
                        </label>

                        <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}"
                            class="font-medium text-blue-600 transition hover:text-blue-800 hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                            <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                            <span class="relative">Sign in</span>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center text-sm text-blue-800">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-blue-600 transition hover:text-blue-800 hover:underline">
                            Register here
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

