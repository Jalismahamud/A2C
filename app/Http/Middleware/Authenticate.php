<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function authenticate($request, array $guards)
    {
        if (Auth::guard('customer')->check()) {
            return Auth::shouldUse('customer');
        }

        $this->unauthenticated($request, $guards);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('customer.login');
        }
    }

}
