@extends('layouts.app')

@section('title', 'Edit Driver - ' . $driver->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('drivers.show', $driver) }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Driver Profile
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 text-white">
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-edit mr-3"></i>Edit Driver Information
            </h1>
            <p class="text-blue-100 mt-1">Update driver details below</p>
        </div>

        <!-- Form -->
        <form id="edit-driver-form" method="POST" action="{{ route('drivers.update', $driver) }}" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Driver Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="driver_name"
                       value="{{ old('name', $driver->name) }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter driver name">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mobile -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mobile Number <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="mobile" 
                       value="{{ old('mobile', $driver->mobile) }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="09XXXXXXXXX">
                @error('mobile')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- License Number -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    License Number <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="license_number" 
                       id="license_number"
                       value="{{ old('license_number', $driver->license_number) }}" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="N01-12345678">
                @error('license_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex-1">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('drivers.show', $driver) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Duplicate Warning Modal -->
<div id="duplicate-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="flex min-h-full items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Similar Driver Names or License Numbers Found</h2>
                    <p class="mt-2 text-sm text-gray-600">The following drivers have similar information. Are you sure you want to update to this?</p>
                    
                    <div id="similar-drivers-list" class="mt-4 space-y-2 max-h-48 overflow-y-auto">
                        <!-- Similar drivers will be inserted here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg bg-gray-500 text-white transition-colors hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="button" id="proceed-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Yes, Update Anyway
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-driver-form');
    const modal = document.getElementById('duplicate-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmDuplicateInput = document.getElementById('confirm_duplicate');
    const similarDriversList = document.getElementById('similar-drivers-list');
    const submitBtn = document.getElementById('submit-btn');

    const showModal = (similarDrivers) => {
        similarDriversList.innerHTML = '';
        similarDrivers.forEach(driver => {
            const div = document.createElement('div');
            div.className = 'p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            div.innerHTML = `
                <p class="font-semibold text-gray-800">${driver.name}</p>
                ${driver.license_number ? `<p class="text-xs text-gray-600">License: ${driver.license_number}</p>` : ''}
                ${driver.mobile ? `<p class="text-xs text-gray-600">Mobile: ${driver.mobile}</p>` : ''}
                <p class="text-xs text-gray-500">Status: ${driver.status}</p>
            `;
            similarDriversList.appendChild(div);
        });

        modal.classList.remove('hidden');
        proceedBtn.focus();
    };

    const hideModal = () => {
        modal.classList.add('hidden');
        confirmDuplicateInput.value = '0';
    };

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (confirmDuplicateInput.value === '1') {
            form.removeEventListener('submit', arguments.callee);
            form.submit();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                body: formData
            });

            const data = await response.json();

            if (data.requires_confirmation) {
                showModal(data.similar_drivers);
            } else if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while checking for duplicates');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Changes';
        }
    });

    cancelBtn.addEventListener('click', hideModal);

    proceedBtn.addEventListener('click', () => {
        confirmDuplicateInput.value = '1';
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

