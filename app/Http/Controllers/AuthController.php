<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        // Attempt login using 'name' instead of 'email'
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // prevent session fixation
            return redirect()->intended(route('dashboard'));
        }

        // Login failed: return with error
        return back()->withErrors([
            'name' => 'ឈ្មោះអ្នកប្រើប្រាស់ ឬ លេខសំងាត់មិនត្រឹមត្រូវ',
        ])->onlyInput('name');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
