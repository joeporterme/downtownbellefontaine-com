<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in, redirect to login page
        if (!backpack_auth()->check()) {
            return redirect()->guest(backpack_url('login'));
        }

        // If logged in but doesn't have access, abort
        if (!backpack_user()->hasAccessToBackpack()) {
            abort(403);
        }

        return $next($request);
    }
}
