@extends('layouts.app')

@section('title', 'Add New Driver')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('drivers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus text-blue-600"></i> Add New Driver
        </h1>

        <form id="create-driver-form" method="POST" action="{{ route('drivers.store') }}">
            @csrf
            <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Driver Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="driver_name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter driver name">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="mobile" required value="{{ old('mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+63 XXX XXX XXXX">
                    @error('mobile')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        License Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="license_number" id="license_number" required value="{{ old('license_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter license number">
                    @error('license_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="on-trip" {{ old('status') == 'on-trip' ? 'selected' : '' }}>On Trip</option>
                        <option value="off-duty" {{ old('status') == 'off-duty' ? 'selected' : '' }}>Off Duty</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                    </label>
                        @foreach($availableDrivers as $availableDriver)
                                {{ $availableDriver->name }} ({{ ucfirst($availableDriver->status) }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">
                    <i class="fas fa-save"></i> Save Driver
                </button>
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
                    <p class="mt-2 text-sm text-gray-600">The following drivers have similar information. Are you sure you want to add another driver?</p>
                    
                    <div id="similar-drivers-list" class="mt-4 space-y-2 max-h-48 overflow-y-auto">
                        <!-- Similar drivers will be inserted here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg bg-gray-500 text-white transition-colors hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="button" id="proceed-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Yes, Add Anyway
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-driver-form');
    const modal = document.getElementById('duplicate-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmDuplicateInput = document.getElementById('confirm_duplicate');
    const similarDriversList = document.getElementById('similar-drivers-list');
    const submitBtn = document.getElementById('submit-btn');

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const showModal = (similarDrivers) => {
        // Build list of similar drivers
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
            // User already confirmed, submit the form normally
            form.removeEventListener('submit', arguments.callee);
            form.submit();
            return;
        }

        // Check for duplicates via AJAX
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken  // FIX: Add CSRF token to headers
                },
                body: formData
            });

            const data = await response.json();

            if (data.requires_confirmation) {
                // Show duplicate warning modal
                showModal(data.similar_drivers);
            } else if (data.success) {
                // No duplicates, redirect to success page
                window.location.href = data.redirect;
            } else {
                // Handle validation errors or other errors
                if (data.errors) {
                    let errorMsg = 'Validation errors:\n';
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMsg += `\n${field}: ${messages.join(', ')};
                    }
                    alert(errorMsg);
                } else {
                    alert(data.message || 'An error occurred');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while checking for duplicates. Check browser console for details.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Driver';
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
