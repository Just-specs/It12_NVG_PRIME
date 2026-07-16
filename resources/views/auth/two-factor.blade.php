@extends('layouts.app')

@section('title', 'Verify Email Code')

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
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/75 backdrop-blur-sm text-blue-900 shadow-2xl shadow-blue-900/20">
            <div class="px-8 pt-8 pb-6 text-center">
                <img
                    src="{{ asset('img/NVG_LOGO_org.png') }}"
                    alt="NVG Prime Movers logo"
                    class="mx-auto mb-4 h-20 w-auto drop-shadow-lg" />
                <h2 class="text-3xl font-bold tracking-tight text-blue-900">
                    Email Verification
                </h2>
                <p class="mt-2 text-sm text-blue-700 font-medium">
                    Enter the 6-digit code sent to
                    <span class="font-bold">{{ $email ?? 'your email address' }}</span>.
                </p>
                <p class="mt-1 text-xs text-blue-600">
                    The code expires in 10 minutes.
                </p>
            </div>

            <div class="space-y-5 px-8 pb-8">
                @if(session('error'))
                    <div class="rounded-xl border border-rose-500/30 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="rounded-xl border border-blue-500/30 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">
                        {{ session('info') }}
                    </div>
                @endif

                @if($errors->has('code'))
                    <div class="rounded-xl border border-rose-500/30 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                        {{ $errors->first('code') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.challenge.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="code" class="mb-2 block text-sm font-semibold text-blue-900">
                            Verification Code
                        </label>
                        <input
                            id="code"
                            name="code"
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            required
                            autofocus
                            class="w-full rounded-2xl border-2 border-blue-300 bg-blue-50 px-4 py-3 text-center text-2xl font-bold tracking-[0.45em] text-blue-950 caret-blue-700 placeholder-blue-300 shadow-lg shadow-blue-900/10 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-200/70"
                            placeholder="000000" />
                    </div>

                    <button
                        type="submit"
                        class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                        <span class="relative">Verify and Continue</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('two-factor.challenge.resend') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded-2xl border-2 border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                        Resend Code
                    </button>
                </form>

                <p class="text-center text-sm text-blue-800">
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 transition hover:text-blue-800 hover:underline">
                        Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
