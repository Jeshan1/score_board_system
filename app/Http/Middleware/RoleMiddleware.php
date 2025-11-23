<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Admin has access to EVERYTHING
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user role is in the allowed roles
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden. Access denied.'], 403);
        }

        return $next($request);
    }
}
