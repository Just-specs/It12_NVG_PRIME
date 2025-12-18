<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Display a listing of dispatchers and admins.
     */
    public function dispatchers()
    {
        $dispatchers = User::whereIn('role', ['admin', 'head_dispatch', 'dispatch'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dispatchers.index', compact('dispatchers'));
    }

    /**
     * Show the form for creating a new dispatcher/admin.
     */
    public function createDispatcher()
    {
        return view('admin.dispatchers.create');
    }

    /**
     * Store a newly created dispatcher/admin in storage.
     */
    public function storeDispatcher(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,head_dispatch,dispatch'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.dispatchers.index')
            ->with('success', 'Account created successfully!');
    }

    /**
     * Show the form for editing the specified dispatcher/admin.
     */
    public function editDispatcher(User $dispatcher)
    {
        // Only allow editing dispatchers and admins
        if (!in_array($dispatcher->role, ['admin', 'head_dispatch', 'dispatch'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.dispatchers.edit', compact('dispatcher'));
    }

    /**
     * Update the specified dispatcher/admin in storage.
     */
    public function updateDispatcher(Request $request, User $dispatcher)
    {
        // Only allow editing dispatchers and admins
        if (!in_array($dispatcher->role, ['admin', 'head_dispatch', 'dispatch'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $dispatcher->id],
            'mobile' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:admin,head_dispatch,dispatch'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $dispatcher->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'role' => $validated['role'],
        ]);

        if ($request->filled('password')) {
            $dispatcher->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('admin.dispatchers.index')
            ->with('success', 'Account updated successfully!');
    }

    /**
     * Remove the specified dispatcher/admin from storage.
     */
    public function destroyDispatcher(User $dispatcher)
    {
        // Only allow deleting dispatchers and admins
        if (!in_array($dispatcher->role, ['admin', 'head_dispatch', 'dispatch'])) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deleting own account
        if ($dispatcher->id === auth()->id()) {
            return redirect()->route('admin.dispatchers.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $dispatcher->delete();

        return redirect()->route('admin.dispatchers.index')
            ->with('success', 'Account deleted successfully!');
    }
}
