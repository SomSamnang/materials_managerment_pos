<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->password_changed_at && $user->password_changed_at->diffInDays(now()) > 90) {
            // Allow access to the password change routes and logout
            if (!$request->routeIs('password.expired') && 
                !$request->routeIs('password.update_expired') && 
                !$request->routeIs('logout')) {
                
                return redirect()->route('password.expired')
                    ->with('warning', __('Your password has expired. Please change it to continue.'));
            }
        }

        return $next($request);
    }
}