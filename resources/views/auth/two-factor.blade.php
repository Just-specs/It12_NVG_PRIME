@extends('layouts.app')

@section('title', 'Verify Code')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800">Two-Factor Verification</h2>
        <p class="mt-2 text-sm text-gray-600">Enter the 6-digit code from your authenticator app to continue.</p>

        @if(session('error'))
            <div class="mt-4 rounded bg-red-100 px-3 py-2 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="mt-4 rounded bg-blue-100 px-3 py-2 text-sm text-blue-700">{{ session('info') }}</div>
        @endif

        <form method="POST" action="{{ route('two-factor.challenge.post') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
            </div>

            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Verify</button>
        </form>
    </div>
</div>
@endsection
