<?php

namespace App\Http\Controllers\backend\agent;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentCustomerController extends Controller
{
    public function index()
    {
        $totalCustomer = Customer::where('status', 1)->where('created_by', Auth::user()->id)->count();
        $todaysCustomer = Customer::where('status', 1)->where('created_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->count();

        return view('backend.agent.customer.index', compact('totalCustomer', 'todaysCustomer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.agent.customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'type' => 'required',
            'phone' => [
                'required',
                Rule::unique('users', 'phone'),
                Rule::unique('customers', 'phone'),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
        ]);

        try {
            DB::beginTransaction();

            // --- Create a customer ---
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
            $customer->approved = 0;
            $customer->status = 1;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $folder = public_path('backend/images/customer/');

                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move($folder, $filename);
                $customer->image = $filename;
            }

            $customer->save();

            DB::commit();

            return redirect()
                ->route('agent.customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

          return view('backend.agent.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'type' => 'required',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($id),
                Rule::unique('customers', 'phone')->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
        ]);

        try {
            DB::beginTransaction();

            // --- Update a Customer ---
            $customer = Customer::findOrFail($id);
            $customer->name = $request->name;
            $customer->address = $request->address;
            $customer->type = $request->type;
            $customer->approved = $request->approved ?? $customer->approved;
            $customer->status = $request->status ?? $customer->status;

            // Only update phone if necessary
            $customer->phone = $request->phone;

            // Handle extra fields by type
            $customer->nid_number = null;
            $customer->school_name = null;
            $customer->student_class = null;
            $customer->vehicle_type = null;
            $customer->license_number = null;

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

            if ($request->hasFile('image')) {
                if ($customer->image && file_exists(public_path('backend/images/customer/' . $customer->image))) {
                    unlink(public_path('backend/images/customer/' . $customer->image));
                }

                $imageFile = $request->file('image');
                $folder = public_path('backend/images/customer/');

                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move($folder, $filename);
                $customer->image = $filename;
            }

            $customer->save();

            DB::commit();

            return redirect()
                ->route('agent.customers.index')
                ->with('success', 'Customer updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Customer Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if ($customer->image && file_exists(public_path('backend/images/customer/' . $customer->image))) {
                unlink(public_path('backend/images/customer/' . $customer->image));
            }

            $customer->delete();

            return response()->json(['message' => 'Customer deleted successfully.']);
        } catch (\Throwable $e) {
            Log::error('Customer Delete Error: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }
}

