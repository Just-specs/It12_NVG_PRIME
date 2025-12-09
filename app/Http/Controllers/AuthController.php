<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        // Validate the login form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // Return back with error
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password');
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
        // Validate the registration form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Passwords do not match',
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
