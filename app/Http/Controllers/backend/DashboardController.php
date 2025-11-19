<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
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
        $totalPendingWalletCount = WalletTransaction::where('status' , 0)->count();
        $totalCompletedWalletCount = WalletTransaction::where('status' , 1)->count();
        $totalProcessingWalletCount = WalletTransaction::where('status' , 2)->count();


        return view('backend.index', compact('agentCount', 'inChargeCount', 'customerActiveCount', 'customerInactiveCount', 'customerRequestCount' , 'totalPendingWalletCount', 'totalCompletedWalletCount', 'totalProcessingWalletCount'));
    }


    public function agentsJson(Request $request)
    {
        if (!Auth::check() || Auth::user()->role != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $roleFilter = $request->query('role');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 25);
        $page = $request->query('page', 1);

        $validPerPage = [25, 50, 100, 500];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 25;
        }

        $query = User::query();

        $query->whereIn('role', [2, 3]);

        $query->where('created_by', Auth::user()->id);

        if ($roleFilter && in_array((int)$roleFilter, [2, 3])) {
            $query->where('role', (int)$roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('nid_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $users = $query
            ->with('addBY:id,name')
            ->select(['id','name','phone','nid_number','address','balance','account_balance','role','status','approved','image','created_by','created_at'])
            ->orderBy('created_at', 'desc')
            ->skip(((int)$page - 1) * (int)$perPage)
            ->take((int)$perPage)
            ->get();

        $data = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'image' => $u->image ? asset($u->image) : null,
                'name' => $u->name,
                'phone' => $u->phone,
                'nid_number' => $u->nid_number,
                'address' => $u->address,
                'balance' => $u->balance,
                'account_balance' => $u->account_balance,
                'role' => $u->role,
                'status' => $u->status,
                'approved' => $u->approved,
                'created_by' => $u->addBY ? $u->addBY->name : null,
                'created_at' => $u->created_at ? $u->created_at->toDateTimeString() : null,
            ];
        });

        $lastPage = (int)ceil($total / (int)$perPage);

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => (int)$perPage,
                'current_page' => (int)$page,
                'last_page' => $lastPage,
            ]
        ]);
    }


    /**
     * Return customers as JSON for AJAX table (admin view).
     */
    public function customersJson(Request $request)
    {
        if (!Auth::check() || Auth::user()->role != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $search = $request->query('search');
        $perPage = $request->query('per_page', 25);
        $page = $request->query('page', 1);

        $validPerPage = [25, 50, 100, 500];
        if (!in_array((int)$perPage, $validPerPage) && $perPage !== 'all') {
            $perPage = 25;
        }

        $query = \App\Models\Customer::query();

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
                'image' => $c->image ? asset('backend/images/customer/' . $c->image) : asset('backend/images/no-image.png'),
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
