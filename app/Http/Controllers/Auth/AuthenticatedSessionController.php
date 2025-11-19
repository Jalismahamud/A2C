<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = User::where('role', 1)->get();
        return redirect()->intended(route('dashboard', absolute: false));
    }


    public function agentInchargeCreate(): View
    {
        return view('auth.agent-or-incharge-login');
    }

    public function agentInchargeStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate1();

        $request->session()->regenerate();

        if(Auth::user()->role == 2){
            return redirect()->intended(route('incharge.dashboard', absolute: false));
        } elseif (Auth::user()->role == 3){
            return redirect()->intended(route('agent.dashboard', absolute: false));
        } else {
            return redirect()->back()->withErrors(['role' => 'Unauthorized role for agent or incharge login.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
