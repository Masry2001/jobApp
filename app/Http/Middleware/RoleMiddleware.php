<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (auth()->check()) {
            $userRole = auth()->user()->role;

            if ($userRole == $role) {
                return $next($request);
            }
        }
        abort(403, 'Unauthorized');
    }
}
