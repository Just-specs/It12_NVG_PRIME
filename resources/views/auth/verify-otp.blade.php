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

    .sidebar,
    #mobile-sidebar-toggle {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .otp-input {
        width: 3rem;
        height: 3.5rem;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        border: 2px solid #bfdbfe;
        border-radius: 0.75rem;
        background: #f0f7ff;
        color: #1e3a8a;
        caret-color: #3b82f6;
        outline: none;
        transition: all 0.2s;
    }

    .otp-input:focus {
        border-color: #3b82f6;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-4px); }
        40%, 80% { transform: translateX(4px); }
    }
    .shake { animation: shake 0.4s ease-in-out; }
</style>
@endpush

@section('content')
<div class="relative flex min-h-screen w-full items-center justify-center px-4 py-8">
    <div class="relative z-10 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-blue-500/20 bg-white/70 backdrop-blur-sm text-blue-900 shadow-2xl shadow-blue-900/20">

            <div class="px-8 pt-8 pb-6 text-center">
                <img
                    src="{{ asset('img/NVG_LOGO_org.png') }}"
                    alt="NVG Prime Movers logo"
                    class="mx-auto mb-4 h-20 w-auto drop-shadow-lg" />
                <h2 class="text-2xl font-bold tracking-tight text-blue-900">
                    Check Your Email
                </h2>
                <p class="mt-2 text-sm text-blue-600 font-medium">
                    We sent a 6-digit verification code to
                </p>
                <p class="mt-1 text-sm font-semibold text-blue-800">
                    {{ $maskedEmail }}
                </p>
            </div>

            <div class="space-y-6 px-8 pb-8">
                <form id="otpForm" action="{{ route('otp.verify.post') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="mb-3 block text-center text-sm font-semibold text-blue-900">
                            Enter Verification Code
                        </label>
                        <div class="flex items-center justify-center gap-2" id="otp-container">
                            @for ($i = 0; $i < 6; $i++)
                                <input
                                    type="text"
                                    maxlength="1"
                                    inputmode="numeric"
                                    pattern="[0-9]"
                                    autocomplete="one-time-code"
                                    class="otp-input"
                                    id="otp_{{ $i }}"
                                    data-index="{{ $i }}"
                                    required>
                            @endfor
                        </div>
                        <input type="hidden" name="otp" id="otp_hidden">
                        @error('otp')
                            <p class="mt-2 text-center text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        @if (session('error'))
                            <p class="mt-2 text-center text-sm text-rose-600">{{ session('error') }}</p>
                        @endif
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-xl shadow-blue-900/30 transition hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-900/40 focus:outline-none focus:ring-4 focus:ring-blue-200/60">
                            <span class="absolute inset-0 bg-white/20 opacity-0 transition group-hover:opacity-100"></span>
                            <span class="relative">Verify & Sign In</span>
                        </button>
                    </div>
                </form>

                <form action="{{ route('otp.resend') }}" method="POST" class="text-center">
                    @csrf
                    <p class="text-sm text-blue-700">
                        Didn't receive the code?
                        <button type="submit" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline transition">
                            Resend code
                        </button>
                    </p>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition">
                        &larr; Back to login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hidden = document.getElementById('otp_hidden');
    const container = document.getElementById('otp-container');

    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const val = this.value.replace(/[^0-9]/g, '');
            this.value = val;
            if (val && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateHidden();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                updateHidden();
            }
            if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/[^0-9]/g, '').split('');
            digits.forEach((digit, i) => {
                if (i < inputs.length) {
                    inputs[i].value = digit;
                }
            });
            const nextIndex = Math.min(digits.length, inputs.length - 1);
            inputs[nextIndex].focus();
            updateHidden();
        });
    });

    function updateHidden() {
        let code = '';
        inputs.forEach(input => code += input.value);
        hidden.value = code;
    }

    document.getElementById('otpForm').addEventListener('submit', function(e) {
        updateHidden();
        if (hidden.value.length !== 6) {
            e.preventDefault();
            container.classList.add('shake');
            setTimeout(() => container.classList.remove('shake'), 500);
            const firstEmpty = Array.from(inputs).find(i => !i.value);
            if (firstEmpty) firstEmpty.focus();
        }
    });

    inputs[0].focus();
})();
</script>
@endsection
