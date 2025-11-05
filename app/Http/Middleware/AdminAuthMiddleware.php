<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        // Check if admin is active
        $admin = Auth::guard('admin')->user();
        if (!$admin->isActive()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}