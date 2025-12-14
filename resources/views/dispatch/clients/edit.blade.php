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

        <form id="edit-client-form" method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="client_name" required value="{{ old('name', $client->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                <a href="{{ route('clients.show', $client) }}" class="px-6 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save"></i> Update Client
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
                    <h2 class="text-lg font-semibold text-gray-800">Similar Client Names Found</h2>
                    <p class="mt-2 text-sm text-gray-600">The following clients have similar names. Are you sure you want to update to this name?</p>
                    
                    <div id="similar-clients-list" class="mt-4 space-y-2 max-h-48 overflow-y-auto">
                        <!-- Similar clients will be inserted here -->
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
    const form = document.getElementById('edit-client-form');
    const modal = document.getElementById('duplicate-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmDuplicateInput = document.getElementById('confirm_duplicate');
    const similarClientsList = document.getElementById('similar-clients-list');
    const submitBtn = document.getElementById('submit-btn');

    const showModal = (similarClients) => {
        // Build list of similar clients
        similarClientsList.innerHTML = '';
        similarClients.forEach(client => {
            const div = document.createElement('div');
            div.className = 'p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            div.innerHTML = `
                <p class="font-semibold text-gray-800">${client.name}</p>
                ${client.email ? `<p class="text-xs text-gray-600">Email: ${client.email}</p>` : ''}
                ${client.company ? `<p class="text-xs text-gray-600">Company: ${client.company}</p>` : ''}
            `;
            similarClientsList.appendChild(div);
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
            // User already confirmed, submit the form
            form.removeEventListener('submit', arguments.callee);
            form.submit();
            return;
        }

        // Check for duplicates
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.requires_confirmation) {
                // Show duplicate warning modal
                showModal(data.similar_clients);
            } else if (data.success) {
                // No duplicates, redirect to success page
                window.location.href = data.redirect;
            } else {
                // Other error
                alert(data.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while checking for duplicates');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Client';
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
