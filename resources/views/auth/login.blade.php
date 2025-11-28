@extends('layouts.app')

@section('body-class', 'bg-[#38B7F7]')

@push('styles')
<style>
    body {
        overflow: hidden;
    }

    body.bg-\[\#38B7F7\] {
        position: relative;
        background: #A5DFF9 url('{{ asset("img/BCO.655ce4cc-cb22-4b9e-ae5d-867a0e717d60.png") }}') center/cover no-repeat;
    }

    body.bg-\[\#38B7F7\]:before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(56, 183, 247, 0.45);
        z-index: -1;
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-6 py-16">

    <div class="relative z-10 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/70 text-blue-900 shadow-xl shadow-blue-900/10">
            <div class="px-8 pt-10 text-center">
                <img
                    src="{{ asset('Img/NVG_LOGO org.png') }}"
                    alt="RVG Prime Movers logo"
                    class="mx-auto mb-4 h-20 w-auto drop-shadow-lg" />
                <h2 class="text-3xl font-semibold tracking-tight">
                    RVG PRIME MOVERS
                </h2>
            </div>

            <form class="space-y-8 px-8 pb-10" action="{{ route('login.post') }}" method="POST">
                @csrf

                @if ($errors->any())
                <div class="flex items-start gap-3 rounded-2xl border border-rose-500/30 bg-rose-500/15 p-4 text-left">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-500/20 text-rose-300">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-rose-100">
                            {{ $errors->first() }}
                        </h3>
                    </div>
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-blue-900">
                            Username:
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
                                class="w-full rounded-2xl border-2 border-blue-300 bg-blue-200 py-3 pl-11 pr-4 text-sm text-blue-950 caret-blue-700 placeholder-blue-500 shadow-lg shadow-blue-900/15 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('email') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                placeholder="Email address"
                                value="{{ old('email') }}">
                        </div>
                    </div>
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
                                class="w-full rounded-2xl border-2 border-blue-300 bg-blue-200 py-3 pl-11 pr-4 text-sm text-blue-950 caret-blue-700 placeholder-blue-500 shadow-lg shadow-blue-900/15 transition focus:border-blue-400 focus:bg-white focus:text-blue-950 focus:outline-none focus:ring-4 focus:ring-blue-200/70 @error('password') border-rose-400/60 focus:ring-rose-500/30 @enderror"
                                placeholder="Password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm text-blue-700">
                    <label class="flex items-center gap-2">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-blue-200 bg-white text-blue-600 focus:ring-blue-200/60 focus:ring-offset-0">
                        <span>Remember me</span>
                    </label>
                    <a
                        href="{{ Route::has('password.request') ? route('password.request') : '#' }}"
                        class="font-medium text-blue-600 transition hover:text-blue-800">
                        Forgot password?
                    </a>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                        <span class="relative">Sign in</span>
                    </button>
                </div>

                <p class="text-center text-sm text-blue-800">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 transition hover:text-blue-800">
                        Register here
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection