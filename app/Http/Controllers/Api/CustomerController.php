<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Customer;
use App\Models\WalletTransaction;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class CustomerController extends Controller
{
    use ImageUploader;


    public function customerList(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            // $user = Auth::user();

            // $search = $request->query('search');

            // $query = Customer::where('created_by', $user->id);

            // if ($search) {
            //     $query->where('name', 'LIKE', "%{$search}%")
            //         ->orWhere('type', 'LIKE', "%{$search}%")
            //         ->orWhere('phone', 'LIKE', "%{$search}%")
            //         ->orWhere('nid_number', 'LIKE', "%{$search}%")
            //         ->orWhere('school_name', 'LIKE', "%{$search}%")
            //         ->orWhere('teacher_name', 'LIKE', "%{$search}%")
            //         ->orWhere('vehicle_type', 'LIKE', "%{$search}%")
            //         ->orWhere('license_number', 'LIKE', "%{$search}%");
            // }


            // $customers = $query->where('approved', 1)->where('created_by', $user->id)->latest()->paginate(50)->appends($request->query());

            // $formattedCustomers = $customers->map(function ($customer) {
            //     return [
            //         'id' => $customer->id,
            //         'name' => $customer->name,
            //         'phone' => $customer->phone,
            //         'address' => $customer->address,
            //         'type' => $customer->type,
            //         'image_url' => $customer->image ? asset('backend/images/customer/' . $customer->image) : null,
            //         'created_by' => $customer->created_by ? $customer->addBY->name : '',
            //         'created_at' => $customer->created_at->toDateTimeString(),

            //     ];
            // });

            $user = Auth::user();
            $search = $request->query('search');

            if ($user->role == 1) {
                $query = Customer::query();
            } elseif ($user->role == 2) {
                $agentIds = User::where('created_by', $user->id)->pluck('id')->toArray();
                $agentIds[] = $user->id;
                $query = Customer::whereIn('created_by', $agentIds);
            } else {
                $query = Customer::where('created_by', $user->id);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('type', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('nid_number', 'LIKE', "%{$search}%")
                        ->orWhere('school_name', 'LIKE', "%{$search}%")
                        ->orWhere('teacher_name', 'LIKE', "%{$search}%")
                        ->orWhere('vehicle_type', 'LIKE', "%{$search}%")
                        ->orWhere('license_number', 'LIKE', "%{$search}%");
                });
            }

            // Filter by approved and paginate
            $customers = $query->where('approved', 1)
                ->latest()
                ->paginate(50)
                ->appends($request->query());

            // Format customer data
            $formattedCustomers = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'type' => $customer->type,
                    'image_url' => $customer->image ? asset('backend/images/customer/' . $customer->image) : null,
                    'created_by' => $customer->created_by ? optional($customer->addBY)->name : '',
                    'created_at' => $customer->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Customer list get successfully.',
                'customer_lists' => $formattedCustomers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving customer list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function customerRequestsList()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $customers = Customer::where('approved', 0)->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
            $formattedCustomers = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'type' => $customer->type,
                    'image_url' => $customer->image ? asset('backend/images/customer/' . $customer->image) : null,
                    'created_by' => $customer->created_by ? $customer->addBY->name : '',
                    'created_at' => $customer->created_at->toDateTimeString(),

                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Customer list get successfully.',
                'customer_lists' => $formattedCustomers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving customer list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approveRequest($id)
    {
        try {

            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $customer = Customer::where('approved', 0)->where('status', 1)
                ->find($id);

            if (!$customer) {
                return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
            }

            if (Auth::user()->balance != 0) {
                $customer->approved = 1;
                $customer->save();

                $agent = User::find($customer->created_by);
                $agent->balance -= 30;
                $agent->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Requested customer approved successfully.',
                    'approved_customer' => new CustomerResource($customer),
                ], 200);
            } else {
                return response()->json(['message' => 'Insufficient balance'], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'address' => 'required|max:255',
                'type' => 'required|in:general,driver,student,agent',
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('customers', 'phone'),
                    Rule::unique('users', 'phone'),
                ],
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                'nid_number' => 'nullable|string|max:50',
                'vehicle_type' => 'nullable|required_if:type,driver',
                'license_number' => 'nullable|required_if:type,driver',
                'school_name' => 'nullable|required_if:type,student',
                'student_class' => 'nullable|required_if:type,student',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            // if (Auth::user()->balance != 0) {

            if (Auth::user()) {
                $customer = new Customer();
                $customer->name = $request->name;
                $customer->phone = $request->phone;
                $customer->address = $request->address;
                $customer->type = $request->type;

                if ($request->type == 'general' || $request->type == 'driver') {
                    $customer->nid_number = $request->nid_number;
                }
                if ($request->type == 'driver') {
                    $customer->vehicle_type = $request->vehicle_type;
                    $customer->license_number = $request->license_number;
                }
                if ($request->type == 'student') {
                    $customer->school_name = $request->school_name;
                    $customer->student_class = $request->student_class;
                }

                $customer->password = Hash::make('12345678');
                $customer->created_by = Auth::id();
                $customer->approved = 1;
                $customer->status = 1;
                $customer->agent_phone = $request->agent_phone;

                if ($request->hasFile('image')) {
                    $customer->image = $this->uploadImage($request->file('image'), 400, 400, 'backend/images/customer/', 75);
                }

                $customer->save();

                $agent = User::find($customer->created_by);
                $agent->balance += 50;
                $agent->save();

                $wallet = new WalletTransaction();
                $wallet->user_id = $agent->id;
                $wallet->amount = 50;
                $wallet->status = 1;
                $wallet->type = 1;
                $wallet->save();

                return response()->json(['message' => 'Customer created successfully', 'data' => new CustomerResource($customer)], 201);
            } else {
                return response()->json(['message' => 'Insufficient balance'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer =  Customer::find($id);

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'address' => 'required|max:255',
                'type' => 'required|in:general,driver,student,agent',
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('customers', 'phone')->ignore($customer->id),
                ],
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                'nid_number' => 'nullable|string|max:50',
                'vehicle_type' => 'nullable|required_if:type,driver',
                'license_number' => 'nullable|required_if:type,driver',
                'school_name' => 'nullable|required_if:type,student',
                'student_class' => 'nullable|required_if:type,student',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->type = $request->type;

            if ($request->type == 'general' || $request->type == 'driver') {
                $customer->nid_number = $request->nid_number;
            }
            if ($request->type == 'driver') {
                $customer->vehicle_type = $request->vehicle_type;
                $customer->license_number = $request->license_number;
            }
            if ($request->type == 'student') {
                $customer->school_name = $request->school_name;
                $customer->student_class = $request->student_class;
            }

            $customer->created_by = Auth::id();
            $customer->agent_phone = $request->agent_phone;



            if ($request->hasFile('image')) {
                if ($customer->image) {
                    $this->deleteOne('backend/images/customer/', $customer->image);
                }

                $imageFile = $request->file('image');
                $customer->image = $this->uploadImage($imageFile, 400, 400, 'backend/images/customer/', 75);
            }

            $customer->save();




            return response()->json(['message' => 'Customer updated successfully', 'data' => new CustomerResource($customer)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getCustomer(string $id)
    {
        try {
            $customers = Customer::where('id', $id)->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
            $formattedCustomers = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'nid_number' => $customer->nid_number,
                    'school_name' => $customer->school_name,
                    'student_class' => $customer->student_class,
                    'vehicle_type' => $customer->vehicle_type,
                    'licence_number' => $customer->license_number,
                    'address' => $customer->address,
                    'type' => $customer->type,
                    'status' => $customer->status,
                    'approved' => $customer->approved,
                    'image_url' => $customer->image ? asset('backend/images/customer/' . $customer->image) : null,
                    'created_by' => $customer->created_by ? $customer->addBY->name : '',
                    'created_at' => $customer->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'customer_info' => $formattedCustomers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving customer list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
