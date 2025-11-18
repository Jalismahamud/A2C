<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first.');
        }
        if (Auth::user()->role !== 1) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
