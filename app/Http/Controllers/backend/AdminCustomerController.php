<?php

namespace App\Http\Controllers\backend;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminCustomerController extends Controller
{
    public function index()
    {
        return view('backend.customer.index');
    }


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

            $query = Customer::query();

            // Searchable fields for customers
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

            // Apply pagination unless 'all' requested
            if ($perPage === 'all') {
                $customers = $query
                    ->with('addBY:id,name')
                    ->select(['id','name','phone','nid_number','school_name','teacher_name','vehicle_type','license_number','address','type','status','approved','image','created_by','created_at'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $customers = $query
                    ->with('addBY:id,name')
                    ->select(['id','name','phone','nid_number','school_name','teacher_name','vehicle_type','license_number','address','type','status','approved','image','created_by','created_at'])
                    ->orderBy('created_at', 'desc')
                    ->skip(((int)$page - 1) * (int)$perPage)
                    ->take((int)$perPage)
                    ->get();
            }

            $data = $customers->map(function ($c) {
                return [
                    'id' => $c->id,
                    'image' => $c->image ? asset($c->image) : null,
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
