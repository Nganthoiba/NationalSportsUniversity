<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = auth()->user(); // Assumes auth middleware ran before this

        if (!$user) {
            // not logged in, let auth middleware handle it
            return $next($request);
        }

        // Prevent redirect loop: allow access to the no-role page itself
        if ($request->is('no-role')) {
            return $next($request);
        }

        // Adjust if your relationship is different.
        $hasRole = !empty($user->getAssignedRoles()); // or if using spatie: $user->getRoleNames()->isNotEmpty()

        if (!$hasRole) {
            return redirect('/no-role');
        }

        return $next($request);
    }
}
