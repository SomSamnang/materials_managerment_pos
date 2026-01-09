<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Require authentication for all actions
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user's profile page.
     */
    public function show()
    {
        return view('profile.show', [
            'pageTitle' => __('Profile'),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate only password
        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', __('Profile updated successfully.'));
    }
}
