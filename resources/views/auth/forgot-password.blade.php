@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="/img/NVG_LOGO org.png" alt="Logo" class="h-16">
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Forgot Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your email address and we'll send you instructions to reset your password.
                </p>
            </div>

            @if(session('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400"></i> Email Address
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        autofocus
                        value="{{ old('email') }}"
                        class="appearance-none relative block w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="your@email.com"
                    >
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Reset Link
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Login
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Remember your password? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
