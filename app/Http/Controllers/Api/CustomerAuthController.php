<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class CustomerAuthController extends Controller
{
    use ImageUploader;

    public function CustomerRegistration(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'address' => 'required|max:255',
                'type' => 'required|in:general,driver,student,agent',
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('customers', 'phone'),
                ],
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                'nid_number' => 'nullable|string|max:50',
                'vehicle_type' => 'nullable|required_if:type,driver',
                'license_number' => 'nullable|required_if:type,driver',
                'school_name' => 'nullable|required_if:type,student',
                'student_class' => 'nullable|required_if:type,student',
                'agent_phone' => 'required|exists:users,phone'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }


            $agent = User::where('phone', $request->agent_phone)->first();
            // return $agent->phone;

            $registration_bonus = App::make('registration_bonus');

            if ($agent) {
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
                $customer->created_by = $agent->id;
                $customer->approved = 0;
                $customer->status = 1;
                $customer->agent_phone = $request->agent_phone;

                if ($request->hasFile('image')) {
                    $customer->image = $this->uploadImage($request->file('image'), 400, 400, 'backend/images/customer/', 75);
                }

                $customer->save();

                $agent = User::find($customer->created_by);
                $agent->balance += $registration_bonus;
                $agent->save();

                $wallet = new WalletTransaction();
                $wallet->user_id = $agent->id;
                $wallet->amount = $registration_bonus;
                $wallet->status = 1;
                $wallet->type = 1;
                $wallet->save();

                return response()->json(['message' => 'Customer created successfully', 'data' => new CustomerResource($customer)], 201);
            } else {
                return response()->json(['message' => 'Requested Agent Phone Number is invalid'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function CustomerLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:customers,phone',
            'password' => 'required',
        ]);

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('customer-token', ['customer'])->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'customer_data' => new CustomerResource($customer),
        ], 200);
    }


    public function CustomerLogout(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $customer->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function getProfile()
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Auth::guard('customer')->user();

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'nid_number' => $customer->nid_number,
            'school_name' => $customer->school_name,
            'student_class' => $customer->student_class,
            'vehicle_type' => $customer->vehicle_type,
            'license_number' => $customer->license_number,
            'address' => $customer->address,
            'image' => asset('backend/images/customer/' . $customer->image) ?? '',
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ]);
    }


    public function updateProfile(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Auth::guard('customer')->user();

        $existingCustomer = Customer::where('phone', $request->phone)
            ->where('id', '!=', $customer->id)
            ->first();

        if ($existingCustomer) {
            return response()->json([
                'message' => 'The phone number has already been taken by another customer.'
            ], 422);
        }

        if (isset($request->image)) {
            $imageDirectory = 'backend/images/customer/';
            $imageFilename = basename($customer->image);

            $this->deleteOne($imageDirectory, $imageFilename);

            $file = $request->image;
            $filename = $this->uploadImage($file, 300, 300, 'backend/images/customer/', true);
            $image = $filename;
        } else {
            $image = $customer->image ?? '';
        }

        $customer->update([
            'name' => ucfirst($request->name),
            'address' => $request->address,
            'phone' => $request->phone,
            'nid_number' => $request->nid_number,
            'school_name' => $request->school_name,
            'student_class' => $request->student_class,
            'vehicle_type' => $request->vehicle_type,
            'license_number' => $request->license_number,
            'image' => $image,
        ]);

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'nid_number' => $customer->nid_number,
            'school_name' => $customer->school_name,
            'student_class' => $customer->student_class,
            'vehicle_type' => $customer->vehicle_type,
            'license_number' => $customer->license_number,
            'address' => $customer->address,
            'image' => asset('backend/images/customer/' . $customer->image) ?? '',
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ]);
    }
}
