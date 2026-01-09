<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordExpirationController extends Controller
{
    public function show()
    {
        return view('auth.password_expired');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = $request->user();
        
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', __('Password changed successfully.'));
    }
}