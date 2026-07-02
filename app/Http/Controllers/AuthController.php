<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\LoginOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password');
        }

        $this->sendOtp($user, $request->ip());

        return redirect()->route('otp.verify')->with('success', 'A verification code has been sent to your email.');
    }

    public function redirectToGoogleLogin()
    {
        session(['google_oauth_flow' => 'login']);

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function redirectToGoogleRegister()
    {
        session(['google_oauth_flow' => 'register']);

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            $referrer = $request->session()->get('google_oauth_flow', 'login');

            if ($referrer === 'login') {
                if (!$user) {
                    return redirect()->route('login')
                        ->with('error', 'This Google account is not registered yet. Please register first.');
                }

                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                }

                $request->session()->forget('google_oauth_flow');
                $this->sendOtp($user, $request->ip());

                return redirect()->route('otp.verify')->with('success', 'A verification code has been sent to your email.');
            }

            if ($referrer === 'register') {
                if ($user) {
                    return redirect()->route('login')
                        ->with('error', 'An account with this email already exists. Please login instead.');
                }

                $request->validate([
                    'email' => [Rule::disposable()],
                ]);

                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);

                $request->session()->forget('google_oauth_flow');
                $this->sendOtp($newUser, $request->ip());

                return redirect()->route('otp.verify')->with('success', 'Account created! A verification code has been sent to your email.');
            }

            return redirect()->route('login')->with('error', 'Something went wrong. Please try again.');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }

    public function showOtpForm(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        $email = $request->session()->get('otp_email');

        if (!$userId || !$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $maskedEmail = substr($email, 0, 1) . '***' . substr($email, strpos($email, '@'));

        return view('auth.verify-otp', compact('maskedEmail'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = $request->session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $otpRecord = DB::table('login_otps')
            ->where('user_id', $userId)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        DB::table('login_otps')
            ->where('id', $otpRecord->id)
            ->update(['is_used' => true]);

        $user = User::findOrFail($userId);

        Auth::login($user, true);

        $request->session()->forget(['otp_user_id', 'otp_email']);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function resendOtp(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        $email = $request->session()->get('otp_email');

        if (!$userId || !$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        DB::table('login_otps')
            ->where('user_id', $userId)
            ->where('is_used', false)
            ->delete();

        $this->sendOtp($user, $request->ip());

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users',
                'max:255',
                Rule::disposable(),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'],
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'email.disposable' => 'Temporary email addresses are not allowed',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout successful!');
    }

    private function sendOtp(User $user, ?string $ipAddress): void
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('login_otps')->insert([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
            'ip_address' => $ipAddress,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['otp_user_id' => $user->id, 'otp_email' => $user->email]);

        Mail::to($user->email)->send(new LoginOtpMail($user->name, $otp));
    }
}
