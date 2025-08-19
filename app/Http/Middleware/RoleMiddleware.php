<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        // 2. Check if the authenticated user has any of the required roles
        // We assume your User model has a 'role' column (e.g., 'admin', 'seller', 'bidder')
        // Or a method like hasRole() if you're using a more complex role/permission package
        foreach ($roles as $role) {
            if ($user->role === $role) { // This line assumes a 'role' column on your users table
                                        // If using a more complex system, this might be $user->hasRole($role)
                return $next($request); // User has the required role, proceed
            }
        }

        // If no matching role is found, deny access
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}