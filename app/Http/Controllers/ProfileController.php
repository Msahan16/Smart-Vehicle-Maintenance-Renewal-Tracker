<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'driver_license_number' => 'nullable|string|max:255',
            'driver_license_expiry' => 'nullable|date',
            'driver_license_front' => 'nullable|image|max:2048',
            'driver_license_back' => 'nullable|image|max:2048',
            'email_notifications' => 'boolean',
        ]);

        // Handle driver license front image
        if ($request->hasFile('driver_license_front')) {
            $validated['driver_license_front'] = $request->file('driver_license_front')->store('documents/driver_licenses', 'public');
        }

        // Handle driver license back image
        if ($request->hasFile('driver_license_back')) {
            $validated['driver_license_back'] = $request->file('driver_license_back')->store('documents/driver_licenses', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
