<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is manager
        // Note: You might want to allow admins here too: in_array($request->user()->role, ['admin', 'manager'])
        if ($request->user()->role !== 'manager') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}