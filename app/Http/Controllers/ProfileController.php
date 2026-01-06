<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'pageTitle' => 'ប្រវត្តិរូប',
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
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('profile.show')->with('success', 'ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្តូរ។');
        }

        return redirect()->route('profile.show')->with('success', 'មិនមានការផ្លាស់ប្តូរទេ។');
    }
}
