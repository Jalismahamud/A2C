<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role != 1) {
            abort(403, 'Unauthorized access.');
        }
        $agentCount = User::where('role', 3)->count();
        $inChargeCount = User::where('role', 2)->count();
        $customerActiveCount = Customer::where('status', 1)->where('approved', 1)->where('created_by', Auth::user()->id)->count();
        $customerInactiveCount = Customer::where('status', 1)->where('approved', 1)->where('created_by', Auth::user()->id)->count();
        $customerRequestCount = Customer::where('status', 1)->count();
        return view('backend.index', compact('agentCount', 'inChargeCount', 'customerActiveCount', 'customerInactiveCount', 'customerRequestCount'));
    }


    public function indexAgent()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, [2, 3])) {
            abort(403, 'Unauthorized access.');
        }

        //Customer list
        $totalCustomer = Customer::where('status' , 1)->count();
        $todaysCustomer = Customer::where('status', 1)->whereDate('created_at', Carbon::today())->count();
        $customers = Customer::with('addBY')->latest()->paginate(10);


        return view('backend.agent.index', compact('totalCustomer','todaysCustomer' , 'customers'));
    }
}
