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
                    Forgot Password?
                </h2>
                <p class="mt-2 text-sm text-blue-600 font-medium">
                    Enter your email to receive a password reset link
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-6 px-8 pb-8">

                @if (session('status'))
                <div class="flex items-start gap-3 rounded-xl border border-green-500/30 bg-green-50 p-4">
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                        <p class="text-xs text-green-700 mt-1">Please check your email inbox and spam folder.</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf

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
                                value="{{ old('email') }}">
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

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                            <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            <span class="relative">Send Reset Link</span>
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
@endsection
