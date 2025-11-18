<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Akses ditolak.');
        }

        // Support comma-separated role lists, e.g. role:guru,admin
        $allowed = array_map('trim', explode(',', $role));

        // Grant access if user's role is in the allowed list, or if user is admin
        if (!in_array($user->role, $allowed, true) && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
