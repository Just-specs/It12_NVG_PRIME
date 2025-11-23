@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-edit text-blue-600"></i> Edit Client
        </h1>

        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name', $client->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mobile Number
                    </label>
                    <input type="text" name="mobile" value="{{ old('mobile', $client->mobile) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('mobile')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name
                    </label>
                    <input type="text" name="company" value="{{ old('company', $client->company) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('company')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('clients.show', $client) }}" class="px-6 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save"></i> Update Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection