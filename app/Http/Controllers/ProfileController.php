<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password updated successfully!');
    }

    public function startTwoFactorSetup(Request $request)
    {
        $user = Auth::user();
        $service = app(TwoFactorService::class);
        $secret = $service->generateSecret();

        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => null,
            'two_factor_required' => false,
            'two_factor_recovery_codes' => null,
        ]);

        $request->session()->put('two_factor_setup_secret', $secret);
        $request->session()->put('two_factor_setup_uri', $service->getProvisioningUri($user->email, $secret));

        return redirect()->route('profile.edit')
            ->with('success', 'Scan the QR code and enter the verification code below to complete setup.');
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();
        $secret = $request->session()->get('two_factor_setup_secret') ?? $user->two_factor_secret;

        if (empty($secret)) {
            return redirect()->route('profile.edit')->with('error', 'Please start two-factor setup first.');
        }

        $service = app(TwoFactorService::class);
        if (!$service->verifyCode($secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'The verification code is invalid.']);
        }

        $codes = $service->generateRecoveryCodes();
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $codes,
            'two_factor_confirmed_at' => now(),
            'two_factor_required' => true,
        ]);

        $request->session()->forget(['two_factor_setup_secret', 'two_factor_setup_uri']);

        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication is now enabled.');
    }

    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_required' => false,
        ]);

        $request->session()->forget(['two_factor_setup_secret', 'two_factor_setup_uri']);

        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication has been disabled.');
    }
}
