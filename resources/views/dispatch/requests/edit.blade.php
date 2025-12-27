@extends('layouts.app')

@section('title', 'Edit Delivery Request')

@section('content')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('requests.index') }}" class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white bg-[#2563EB] rounded-full hover:bg-[#1D4ED8] transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-plus-circle text-blue-600"></i> New Delivery Request
        </h1>

        <form id="create-request-form" method="POST" action="{{ route('requests.update', $request) }}">
            @csrf
                @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Method <span class="text-red-500">*</span>
                    </label>
                    <select name="contact_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="mobile">Mobile Call</option>
                        <option value="group_chat">Group Chat</option>
                        <option value="email">Email</option>
                    </select>
                </div>

                <!-- ATW Reference -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ATW Reference <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="atw_reference" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter ATW reference number">
                    @error('atw_reference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pickup Location -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pickup Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pickup_location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter pickup location">
                    @error('pickup_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delivery Location -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="delivery_location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter delivery location">
                    @error('delivery_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <!-- EIR Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        EIR Number
                    </label>
                    <input type="text" name="eir_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Equipment Interchange Receipt number">
                    @error('eir_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Booking Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Booking Number
                    </label>
                    <input type="text" name="booking_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Shipping booking reference">
                    @error('booking_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Container Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Container Number
                    </label>
                    <input type="text" name="container_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., WHSU 816908-2">
                    @error('container_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seal Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seal Number
                    </label>
                    <input type="text" name="seal_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Security seal number">
                    @error('seal_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
<!-- Container Size -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Container Size <span class="text-red-500">*</span>
                    </label>
                    <select name="container_size" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="20ft">20ft</option>
                        <option value="40ft">40ft</option>
                        <option value="40ft HC">40ft HC</option>
                    </select>
                </div>

                <!-- Container Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Container Type <span class="text-red-500">*</span>
                    </label>
                    <select name="container_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="standard">Standard</option>
                        <option value="refrigerated">Refrigerated</option>
                        <option value="open_top">Open Top</option>
                        <option value="flat_rack">Flat Rack</option>
                </div>

                <!-- Shipping Line -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Shipping Line
                    </label>
                    <input type="text" name="shipping_line" value="{{ old('shipping_line', $request->shipping_line) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., WANHAI, CMA, MAERSK">
                    @error('shipping_line')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shipper Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Shipper Name
                    </label>
                    <input type="text" name="shipper_name" value="{{ old('shipper_name', $request->shipper_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Shipper company name">
                    @error('shipper_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Container Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Container Status
                    </label>
                    <select name="container_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="loaded" {{ old('container_status', $request->container_status) == 'loaded' ? 'selected' : '' }}>Loaded</option>
                        <option value="empty" {{ old('container_status', $request->container_status) == 'empty' ? 'selected' : '' }}>Empty</option>
                        <option value="return" {{ old('container_status', $request->container_status) == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                    @error('container_status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EIR Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        EIR Time
                    </label>
                    <input type="time" name="eir_time" value="{{ old('eir_time', $request->eir_time ? $request->eir_time->format('H:i') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('eir_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred Schedule -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Schedule <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="preferred_schedule" required min="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div id="holiday-warning" class="mt-2 hidden items-start gap-2 rounded-lg border border-yellow-300 bg-yellow-50 px-3 py-2 text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mt-1"></i>
                        <div>
                            <p class="font-semibold" id="holiday-warning-title"></p>
                            <p id="holiday-warning-details" class="text-xs text-yellow-700"></p>
                        </div>
                    </div>
                    @error('preferred_schedule')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes
                    </label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Any special instructions or notes"></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end mt-6">
                <button type="button" id="open-confirm-modal" class="px-6 py-2 bg-[#2563EB] text-white rounded-lg transition-colors hover:bg-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">
                    <i class="fas fa-save"></i> Create Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div id="submit-confirm-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="submit-confirm-title">
    <div class="flex min-h-full items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
            <h2 id="submit-confirm-title" class="text-lg font-semibold text-gray-800">Are you sure you want to submit this request?</h2>
            <p class="mt-2 text-sm text-gray-600">Review the details before sending the request to dispatch.</p>
            <div class="mt-6 flex justify-center gap-3">
                <button type="button" id="confirm-no-btn" class="px-4 py-2 rounded-lg bg-red-500 text-white transition-colors hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 active:bg-red-700">Cancel</button>
                <button type="button" id="confirm-yes-btn" class="px-4 py-2 rounded-lg bg-[#2563EB] text-white hover:bg-[#1D4ED8] focus:outline-none">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('create-request-form');
        const openModalBtn = document.getElementById('open-confirm-modal');
        const modal = document.getElementById('submit-confirm-modal');
        const confirmYesBtn = document.getElementById('confirm-yes-btn');
        const confirmNoBtn = document.getElementById('confirm-no-btn');
        const scheduleInput = document.querySelector('input[name="preferred_schedule"]');
        const holidayWarning = document.getElementById('holiday-warning');
        const holidayWarningTitle = document.getElementById('holiday-warning-title');
        const holidayWarningDetails = document.getElementById('holiday-warning-details');
        const holidays = @json($holidays ?? collect());

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

        const checkHoliday = (value) => {
            if (!value) {
                holidayWarning.classList.add('hidden');
                return;
            }

            const selectedDate = new Date(value);
            if (Number.isNaN(selectedDate.getTime())) {
                holidayWarning.classList.add('hidden');
                return;
            }

            const selectedDateString = selectedDate.toISOString().slice(0, 10);
            const match = holidays.find((holiday) => holiday.date === selectedDateString);

            if (match) {
                holidayWarningTitle.textContent = `${match.name} (${match.date_display})`;
                holidayWarningDetails.textContent = match.description ?? 'Selected date is a national holiday.';
                holidayWarning.classList.remove('hidden');
            } else {
                holidayWarning.classList.add('hidden');
            }
        };

        scheduleInput.addEventListener('change', (event) => {
            checkHoliday(event.target.value);
        });

        // Initial check in case browser pre-fills the control
        if (scheduleInput.value) {
            checkHoliday(scheduleInput.value);
        }

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



