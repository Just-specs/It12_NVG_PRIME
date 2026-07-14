<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Validates a Google reCAPTCHA v2 response token by verifying it
 * server-to-server against https://www.google.com/recaptcha/api/siteverify.
 *
 * Configuration is read from config('services.recaptcha'):
 *   - enabled    : bool  (if false, the rule always passes — useful for local dev / tests)
 *   - secret_key : string
 *   - verify_url : string (override point for tests)
 *
 * Usage:
 *   $request->validate([
 *       'g-recaptcha-response' => ['required', new RecaptchaRule],
 *   ]);
 */
class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Allow disabling captcha in non-production environments (config-driven).
        if (! config('services.recaptcha.enabled', true)) {
            return;
        }

        $secret = config('services.recaptcha.secret_key');
        $verifyUrl = config('services.recaptcha.verify_url', 'https://www.google.com/recaptcha/api/siteverify');

        if (empty($secret)) {
            // Fail closed — if the site is misconfigured, do not silently let traffic through.
            Log::warning('reCAPTCHA secret key is not configured.');
            $fail('CAPTCHA verification is not configured. Please contact the administrator.');
            return;
        }

        if (empty($value) || ! is_string($value)) {
            $fail('Please complete the CAPTCHA.');
            return;
        }

        try {
            $response = Http::timeout(5)
                ->asForm()
                ->post($verifyUrl, [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA verification request failed', ['error' => $e->getMessage()]);
            $fail('CAPTCHA verification service is unavailable. Please try again.');
            return;
        }

        if (! $response->successful()) {
            Log::warning('reCAPTCHA verification returned non-2xx', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            $fail('CAPTCHA verification failed. Please try again.');
            return;
        }

        $payload = $response->json();

        if (! ($payload['success'] ?? false)) {
            // Do NOT expose Google's error-codes to the user; log them instead.
            Log::info('reCAPTCHA verification rejected', [
                'errors'     => $payload['error-codes'] ?? [],
                'remote_ip'  => request()->ip(),
            ]);
            $fail('CAPTCHA verification failed. Please try again.');
            return;
        }
    }
}
