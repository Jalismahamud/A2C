<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InchargeMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first.');
        }

        $user = Auth::user();

        if (Auth::user()->role !== 2) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
