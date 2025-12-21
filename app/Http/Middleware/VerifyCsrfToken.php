<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes that need to be excluded from CSRF verification
    ];

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        // Get the token from the request
        $token = $this->getTokenFromRequest($request);

        // Log for debugging on Railway
        if (config('app.env') === 'production') {
            \Log::debug('CSRF Token Validation', [
                'session_token' => $request->session()->token(),
                'request_token' => $token,
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
        }

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }
}
