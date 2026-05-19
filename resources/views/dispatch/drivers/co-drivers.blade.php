@extends('layouts.app')

@section('title', 'Manage Co-Drivers - ' . $driver->name)

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manage Co-Drivers</h1>
                <div class="mt-2 flex items-center gap-3">
                    @if($driver->photo_url)
                        <img src="{{ $driver->photo_url }}" alt="{{ $driver->name }} photo" class="h-12 w-12 rounded-full object-cover border">
                    @else
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                    @endif
                    <p class="text-gray-600">Driver: <strong>{{ $driver->name }}</strong></p>
                </div>
            </div>
            <a href="{{ route('drivers.show', $driver) }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Driver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Co-Drivers -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-friends text-blue-600"></i> Current Co-Drivers
            </h2>
            
            @php
                $allCoDrivers = $driver->getAllCoDrivers();
            @endphp
            
            @if($allCoDrivers->count() > 0)
                <div class="space-y-3">
                    @foreach($allCoDrivers as $coDriver)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                @if($coDriver->photo_url)
                                    <img src="{{ $coDriver->photo_url }}" alt="{{ $coDriver->name }} photo" class="h-12 w-12 rounded-full object-cover border">
                                @else
                                    <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $coDriver->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $coDriver->license_number }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('drivers.removeCoDriver', [$driver, $coDriver]) }}" 
                                  onsubmit="return confirm('Remove {{ $coDriver->name }} as co-driver?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1.5 bg-red-500 text-white text-sm rounded-md hover:bg-red-600 transition">
                                    <i class="fas fa-times mr-1"></i> Remove
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-user-slash text-4xl mb-2"></i>
                    <p>No co-drivers assigned</p>
                </div>
            @endif
        </div>

        <!-- Add Co-Driver -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-plus text-green-600"></i> Add Co-Driver
            </h2>
            
            <form method="POST" action="{{ route('drivers.addCoDriver', $driver) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="co_driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Driver
                    </label>
                    <div class="space-y-2 max-h-96 overflow-y-auto rounded-lg border border-gray-200 p-2 @error('co_driver_id') border-red-500 @enderror">
                        @php $hasAvailableCoDriver = false; @endphp
                        @foreach($availableDrivers as $availableDriver)
                            @if(!$driver->hasCoDriver($availableDriver->id))
                                @php $hasAvailableCoDriver = true; @endphp
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 transition hover:border-blue-400 hover:bg-blue-50">
                                    <input type="radio" name="co_driver_id" value="{{ $availableDriver->id }}" required class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    @if($availableDriver->photo_url)
                                        <img src="{{ $availableDriver->photo_url }}" alt="{{ $availableDriver->name }} photo" class="h-12 w-12 rounded-full object-cover border">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    @endif
                                    <span>
                                        <span class="block font-semibold text-gray-800">{{ $availableDriver->name }}</span>
                                        <span class="block text-sm text-gray-600">{{ $availableDriver->license_number }}</span>
                                    </span>
                                </label>
                            @endif
                        @endforeach
                        @unless($hasAvailableCoDriver)
                            <div class="py-6 text-center text-gray-500">
                                <i class="fas fa-user-slash text-3xl text-gray-300"></i>
                                <p class="mt-2 text-sm">No available drivers to add as co-drivers.</p>
                            </div>
                        @endunless
                    </div>
                    @error('co_driver_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> Add Co-Driver
                </button>
            </form>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>About Co-Drivers:</strong><br>
                            - Co-drivers work together on trips<br>
                            - One driver can have multiple co-drivers<br>
                            - Relationship works both ways<br>
                            - No conflicts or duplicates allowed
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
