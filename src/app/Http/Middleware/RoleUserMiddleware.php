<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        $user = $request->user();

        if (!empty(array_intersect($user->role, $role))) {
            return $next($request);
        }


        $return = match (true) {
            in_array('operator', $user->role) => to_route('dashboard'),
            in_array('evaluator', $user->role) => to_route('home'),
            default => abort(403)
        };
        return $return;
    }
}
