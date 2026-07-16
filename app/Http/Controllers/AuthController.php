<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginVerificationCodeNotification;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $validationRules = [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];

        if (config('services.recaptcha.enabled', false)) {
            $validationRules['g-recaptcha-response'] = ['required', new RecaptchaRule];
        }

        $validated = $request->validate($validationRules, [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA.',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Auth::validate($credentials)) {
            $this->sendLoginVerificationCode($request, $user, $request->has('remember'));

            return redirect()
                ->route('two-factor.challenge')
                ->with('info', 'A 6-digit verification code has been sent to your email.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password');
    }

    public function showTwoFactorChallenge(Request $request)
    {
        if (!$request->session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor', [
            'email' => $request->session()->get('two_factor_email'),
            'expiresAt' => $request->session()->get('two_factor_expires_at'),
        ]);
    }

    public function verifyTwoFactorChallenge(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'The verification code is required',
            'code.digits' => 'The verification code must be 6 digits',
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        $remember = (bool) $request->session()->get('two_factor_remember', false);
        $codeHash = $request->session()->get('two_factor_code_hash');
        $expiresAt = $request->session()->get('two_factor_expires_at');

        if (!$userId || !$codeHash || !$expiresAt) {
            $request->session()->forget($this->loginVerificationSessionKeys());
            return redirect()->route('login')->with('error', 'Your verification session expired. Please log in again.');
        }

        if (now()->greaterThan($expiresAt)) {
            $request->session()->forget(['two_factor_code_hash', 'two_factor_expires_at']);
            return back()->with('error', 'The verification code expired. Please request a new code.');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget($this->loginVerificationSessionKeys());
            return redirect()->route('login')->with('error', 'Your account could not be found. Please log in again.');
        }

        if (!Hash::check($request->input('code'), $codeHash)) {
            return back()->with('error', 'Invalid verification code.');
        }

        Auth::login($user, $remember);
        $request->session()->forget($this->loginVerificationSessionKeys());
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function resendTwoFactorChallenge(Request $request)
    {
        $userId = $request->session()->get('two_factor_user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Your verification session expired. Please log in again.');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget($this->loginVerificationSessionKeys());
            return redirect()->route('login')->with('error', 'Your account could not be found. Please log in again.');
        }

        $this->sendLoginVerificationCode(
            $request,
            $user,
            (bool) $request->session()->get('two_factor_remember', false)
        );

        return back()->with('info', 'A new verification code has been sent to your email.');
    }

    private function sendLoginVerificationCode(Request $request, User $user, bool $remember): void
    {
        $code = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        $request->session()->put([
            'two_factor_user_id' => $user->id,
            'two_factor_email' => $user->email,
            'two_factor_remember' => $remember,
            'two_factor_code_hash' => Hash::make($code),
            'two_factor_expires_at' => $expiresAt,
        ]);

        $user->notify(new LoginVerificationCodeNotification($code, 10));
    }

    private function loginVerificationSessionKeys(): array
    {
        return [
            'two_factor_user_id',
            'two_factor_email',
            'two_factor_remember',
            'two_factor_code_hash',
            'two_factor_expires_at',
        ];
    }

    /**
     * Redirect to Google OAuth page for LOGIN (existing users only).
     */
    public function redirectToGoogleLogin()
    {
        // Store in session that this is a login flow
        session(['google_oauth_flow' => 'login']);
        
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Redirect to Google OAuth page for REGISTER (new users).
     */
    public function redirectToGoogleRegister()
    {
        // Store in session that this is a register flow
        session(['google_oauth_flow' => 'register']);
        
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback - works for both LOGIN and REGISTER flows.
     * Determines the flow based on which route was used (login or register).
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this Google ID or email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            // Determine if this is a login or register attempt based on the referrer
            $referrer = $request->session()->get('google_oauth_flow', 'login');
            
            // LOGIN FLOW - User clicked "Sign in with Google" from login page
            if ($referrer === 'login') {
                if (!$user) {
                    // User doesn't exist - redirect to login with toast message
                    return redirect()->route('login')
                        ->with('error', 'This Google account is not registered yet. Please register first.');
                }

                // Update existing user with Google info if not already set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                }

                // Log the user in
                Auth::login($user, true);

                // Clear the session flag
                $request->session()->forget('google_oauth_flow');

                return redirect()->route('dashboard')->with('success', 'Successfully logged in with Google!');
            }

            // REGISTER FLOW - User clicked "Sign up with Google" from register page
            if ($referrer === 'register') {
                if ($user) {
                    // User already exists - redirect to login
                    return redirect()->route('login')
                        ->with('error', 'An account with this email already exists. Please login instead.');
                }

                // Create new user with default 'user' role
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // Random password for OAuth users
                    'role' => 'user', // Set default role
                    'email_verified_at' => now(), // Auto-verify Google users
                ]);

                // Clear the session flag
                $request->session()->forget('google_oauth_flow');

                // DO NOT auto-login - redirect to login page
                return redirect()->route('login')
                    ->with('success', 'Account created successfully with Google! Please login to continue.');
            }

            // Default fallback - shouldn't reach here
            return redirect()->route('login')->with('error', 'Something went wrong. Please try again.');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'],
        ];

        if (config('services.recaptcha.enabled', false)) {
            $validationRules['g-recaptcha-response'] = ['required', new RecaptchaRule];
        }

        // Validate the registration form
        $validated = $request->validate($validationRules, [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA.',
        ]);

        // Create new user with default 'user' role
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Set default role to 'user'
        ]);

        // DO NOT auto-login the user - redirect to login page instead
        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout successful!');
    }
}
