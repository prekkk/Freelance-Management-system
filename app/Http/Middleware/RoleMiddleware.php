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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the user's role
            $userRole = Auth::user()->role;

            // Check if the user's role is allowed
            if (in_array($userRole, $roles)) {
                // User's role is allowed, proceed with the request
                return $next($request);
            }
        }

        // User's role is not allowed, redirect or return an error
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}