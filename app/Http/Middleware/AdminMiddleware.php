<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // You can add additional admin role checks here if you have roles
        // For now, we'll assume all authenticated users in admin area are admins
        
        return $next($request);
    }
}