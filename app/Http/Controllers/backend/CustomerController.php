<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\ImageUploader;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use ImageUploader;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('backend.agent.customer.list', compact('customers'));
    }

    public function agentList()
    {
        $users = User::latest()->where('role', 1)->where('approved', 0)->where('created_by', Auth::user()->id)->get();
        return view('backend.agent.customer.agent-list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.agent.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'address' => 'required|max:255',
    //         'type' => 'required',
    //         'phone' => [
    //             'required',
    //             Rule::unique('users', 'phone'),
    //             Rule::unique('customers', 'phone'),
    //         ],
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ], [
    //         'phone.unique' => 'This phone number is already in use. Please use a different one.',
    //     ]);
    //     if ($request->type == 'agent') {
    //         $user = new User();
    //         $user->name = $request->name;
    //         $user->phone = $request->phone;
    //         $user->address = $request->address;
    //         $user->nid_number = $request->nid_number;
    //         $user->role = 2;
    //         $user->balance = 1500;
    //         $user->password = Hash::make('12345678');
    //         $user->created_by = Auth::user()->id;
    //         $user->approved = 0;
    //         $user->status = 1;


    //         if ($request->hasFile('image')) {
    //             $imageFile = $request->file('image');
    //             $width = 400;
    //             $height = 400;
    //             $folder = 'backend/images/user/';
    //             $user->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
    //         }

    //         $user->save();
    //         return redirect()->route('customers.agent-index')->with('success', 'Agent Data Created successfully.');
    //     }

    //     $customer = new Customer();
    //     $customer->name = $request->name;
    //     $customer->phone = $request->phone;
    //     $customer->address = $request->address;
    //     $customer->type = $request->type;
    //     if ($request->type == 'general' || $request->type == 'driver') {
    //         $customer->nid_number = $request->nid_number;
    //     }
    //     if ($request->type == 'driver') {
    //         $customer->vehicle_type = $request->vehicle_type;
    //         $customer->license_number = $request->license_number;
    //     }
    //     if ($request->type == 'student') {
    //         $customer->school_name = $request->school_name;
    //         $customer->student_class = $request->student_class;
    //     }
    //     $customer->password = Hash::make('12345678');
    //     $customer->created_by = Auth::user()->id;
    //     $customer->approved = 0;
    //     $customer->status = 1;
    //     $customer->agent_phone = $request->agent_phone;

    //     if ($request->hasFile('image')) {
    //         $imageFile = $request->file('image');
    //         $width = 400;
    //         $height = 400;
    //         $folder = 'backend/images/customer/';
    //         $customer->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
    //     }

    //     $customer->save();
    //     // return redirect()->route('customers.index')->with('success', 'Customer Data Created successfully.');
    //      return redirect()->route('agent-or-incharge.dashboard')->with('success', 'Customer Data Created successfully.');
    // }

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

            if ($request->type == 'agent') {
                $user = new User();
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->nid_number = $request->nid_number;
                $user->role = 2;
                $user->balance = 1500;
                $user->password = Hash::make('12345678');
                $user->created_by = Auth::id();
                $user->approved = 0;
                $user->status = 1;

                if ($request->hasFile('image')) {
                    $imageFile = $request->file('image');

                    $folder = public_path('backend/images/user/');

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                    $imageFile->move($folder, $filename);
                    $user->image = $filename;
                }


                $user->save();

                DB::commit();

                return redirect()
                    ->route('customers.agent-index')
                    ->with('success', 'Agent created successfully.');
            }

            // --- Otherwise, create a customer ---
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
            $customer->phone = $request->agent_phone;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');

                $folder = public_path('backend/images/customer/');

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                $imageFile->move($folder, $filename);
                $customer->image = $filename;
            }

            $customer->save();

            DB::commit();

            return redirect()->route('agent-or-incharge.dashboard')->with('success', 'Customer created successfully.');
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

            // --- If it's an Agent update ---
            if ($request->type == 'agent') {
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->nid_number = $request->nid_number;
                $user->approved = $request->approved ?? $user->approved;
                $user->status = $request->status ?? $user->status;
                $user->updated_by = Auth::id();



                if ($request->hasFile('image')) {

                    if ($user->image && file_exists(public_path('backend/images/user/' . $user->image))) {
                        unlink(public_path('backend/images/user/' . $user->image));
                    }

                    $imageFile = $request->file('image');

                    $folder = public_path('backend/images/user/');

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                    $imageFile->move($folder, $filename);
                    $user->image = $filename;
                }

                $user->save();

                DB::commit();
                return redirect()
                    ->route('customers.agent-index')
                    ->with('success', 'Agent updated successfully.');
            }

            // --- Otherwise, update a Customer ---
            $customer = Customer::findOrFail($id);
            $customer->name = $request->name;
            $customer->address = $request->address;
            $customer->type = $request->type;
            $customer->approved = $request->approved ?? $customer->approved;
            $customer->status = $request->status ?? $customer->status;
            $customer->updated_by = Auth::id();

            // Only update phone if necessary
            $customer->phone = $request->phone;

            // Handle extra fields by type
            $customer->nid_number = null;
            $customer->school_name = null;
            $customer->teacher_name = null;
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
                $customer->teacher_name = $request->teacher_name;
            }



            if ($request->hasFile('image')) {
                if ($customer->image && file_exists(public_path('backend/images/customer/' . $customer->image))) {
                    unlink(public_path('backend/images/customer/' . $customer->image));
                }

                $imageFile = $request->file('image');

                $folder = public_path('backend/images/customer/');

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                $imageFile->move($folder, $filename);
                $customer->image = $filename;
            }

            $customer->save();

            DB::commit();

            return redirect()
                ->route('agent-or-incharge.dashboard')
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

            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }
}
