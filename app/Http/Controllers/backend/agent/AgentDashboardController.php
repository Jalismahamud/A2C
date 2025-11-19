<?php

namespace App\Http\Controllers\backend\agent;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentDashboardController extends Controller
{
    public function index()
    {

        if (!Auth::check() || !in_array(Auth::user()->role, [3])) {
            abort(403, 'Unauthorized access.');
        }
        $totalCustomer = Customer::where('status', 1)->where('created_by', Auth::user()->id)->count();
        $todaysCustomer = Customer::where('status', 1)->where('created_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->count();
        $customers = Customer::with('addBY')->where('created_by', Auth::user()->id)->latest()->paginate(10);


        return view('backend.agent.index', compact('totalCustomer', 'todaysCustomer', 'customers'));
    }


    public function customersJson(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, [2, 3])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $validPerPage = [10, 25, 50, 100, 500];
        if (!in_array((int)$perPage, $validPerPage) && $perPage !== 'all') {
            $perPage = 10;
        }

        $query = Customer::query();
        // only customers created by this agent/incharge
        $query->where('created_by', Auth::user()->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('nid_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%")
                    ->orWhere('teacher_name', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        if ($perPage === 'all') {
            $customers = $query->with('addBY')->orderBy('created_at', 'desc')->get();
        } else {
            $customers = $query->with('addBY')->orderBy('created_at', 'desc')
                ->skip(((int)$page - 1) * (int)$perPage)
                ->take((int)$perPage)
                ->get();
        }

        $data = $customers->map(function ($c) {
            return [
                'id' => $c->id,
                'image' => $c->image ? url('backend/images/customer/' . $c->image) : url('backend/images/no-image.png'),
                'name' => $c->name,
                'phone' => $c->phone,
                'nid_number' => $c->nid_number,
                'school_name' => $c->school_name,
                'teacher_name' => $c->teacher_name,
                'vehicle_type' => $c->vehicle_type,
                'license_number' => $c->license_number,
                'address' => $c->address,
                'type' => $c->type,
                'status' => $c->status,
                'approved' => $c->approved,
                'created_by' => $c->addBY ? $c->addBY->name : null,
                'created_at' => $c->created_at ? $c->created_at->toDateTimeString() : null,
            ];
        });

        $lastPage = $perPage === 'all' ? 1 : (int)ceil($total / (int)$perPage);

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage === 'all' ? $total : (int)$perPage,
                'current_page' => (int)$page,
                'last_page' => $lastPage,
            ]
        ]);
    }
}
