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

        $allowedRoles = array_map(function($item){
            return strtolower($item);
        }, $roles);

        $assignedRoles = array_map(function($item){
            return strtolower($item);
        }, $user->getAssignedRoles());

        //dd($allowedRoles, $assignedRoles);

        if (!$user || empty(array_intersect($assignedRoles, $allowedRoles))) {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}
