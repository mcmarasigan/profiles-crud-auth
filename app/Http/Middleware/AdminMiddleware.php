<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware — Restricts route access to admin users only.
 * BONUS: Role-based access control middleware.
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Denies access if the authenticated user is not an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
