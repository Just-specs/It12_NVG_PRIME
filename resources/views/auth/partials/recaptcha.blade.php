@php
    $recaptchaEnabled = config('services.recaptcha.enabled', false);
    $recaptchaSiteKey = config('services.recaptcha.site_key');
@endphp

@if($recaptchaEnabled && $recaptchaSiteKey)
    @once
        @push('scripts')
            <script src="{{ config('services.recaptcha.script_src', 'https://www.google.com/recaptcha/api.js') }}"
                    async defer></script>
        @endpush
    @endonce

    <div class="pt-2 flex justify-center">
        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
    </div>
    @error('g-recaptcha-response')
        <p class="text-center text-sm text-rose-600 font-medium">{{ $message }}</p>
    @enderror
@endif
