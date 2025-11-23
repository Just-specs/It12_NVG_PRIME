@extends('layouts.app')

@section('title', 'Add New Client')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus text-blue-600"></i> Add New Client
        </h1>

        <form id="create-client-form" method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter client name">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="client@example.com">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mobile Number
                    </label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+63 XXX XXX XXXX">
                    @error('mobile')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name
                    </label>
                    <input type="text" name="company" value="{{ old('company') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Company name (optional)">
                    @error('company')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button type="button" id="open-confirm-modal" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">
                    <i class="fas fa-save"></i> Save Client
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div id="submit-confirm-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="submit-confirm-title">
    <div class="flex min-h-full items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h2 id="submit-confirm-title" class="text-lg font-semibold text-gray-800">Are you sure you want to create this client?</h2>
            <p class="mt-2 text-sm text-gray-600">Review the details before saving the new client.</p>
            <div class="mt-6 flex justify-center gap-3">
                <button type="button" id="confirm-no-btn" class="px-4 py-2 rounded-lg bg-red-500 text-white transition-colors hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 active:bg-red-700">Cancel</button>
                <button type="button" id="confirm-yes-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-blue-700 focus:outline-none">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('create-client-form');
        const openModalBtn = document.getElementById('open-confirm-modal');
        const modal = document.getElementById('submit-confirm-modal');
        const confirmYesBtn = document.getElementById('confirm-yes-btn');
        const confirmNoBtn = document.getElementById('confirm-no-btn');

        const showModal = () => {
            modal.classList.remove('hidden');
            confirmYesBtn.focus();
        };

        const hideModal = () => {
            modal.classList.add('hidden');
        };

        openModalBtn.addEventListener('click', () => {
            if (form.checkValidity()) {
                showModal();
            } else {
                form.reportValidity();
            }
        });

        confirmNoBtn.addEventListener('click', hideModal);

        confirmYesBtn.addEventListener('click', () => {
            hideModal();
            form.submit();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                hideModal();
            }
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    });
</script>

@endsection