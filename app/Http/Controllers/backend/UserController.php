<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PackageBalance;
use App\Models\User;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ImageUploader;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->where('role', 1)->get();
        return view('backend.user.list', compact('users'));
    }

    public function agentList()
    {
        $agents = User::latest()->whereIn('role', [2,3])->get();
        return view('backend.user.agent-incharge-list', compact('agents'));
    }

    public function packageBalance($id)
    {
        $agent = User::find($id);
        $package_balance = PackageBalance::where('user_id', $agent->id)->get();
        return view('backend.user.package_balance', compact('package_balance'));
    }


    public function customerList(Request $request)
    {
        $customersQuery = Customer::query();

        if (!empty($request['searchData'])) {
            $searchTerms = $request['searchData'];
            $customersQuery->where(function ($q) use ($searchTerms) {
                $q->where('name', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('type', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('phone', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('nid_number', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('school_name', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('teacher_name', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('vehicle_type', 'LIKE', "%{$searchTerms}%")
                    ->orWhere('license_number', 'LIKE', "%{$searchTerms}%");
            })->orWhereHas('addBY', function ($query) use ($searchTerms) {
                $query->where('name', 'LIKE', "%{$searchTerms}%");
            });
        }

        $perPage = isset($request['perPage']) ? intval($request['perPage']) : 100;
        $currentPage = max(1, $request->input('page', 1));
        $startIndex = ($currentPage - 1) * $perPage;
        $customers =  $customersQuery->where('approved', 1)->latest()->paginate($perPage);


        if ($request->ajax()) {
            return view('backend.user.customer_list_table', compact('customers', 'startIndex'))->render();
        } else {
            return view('backend.user.customer-list', compact('customers', 'startIndex'));
        }
    }

    public function customerEdit($id)
    {
        $customer = Customer::find($id);
        return view('backend.user.customer-edit', compact('customer'));
    }


    public function customerUpdate(Request $request, $id)
    {
        $customer = Customer::find($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'type' => 'required',
            'phone' => [
                'required',
                Rule::unique('customers', 'phone')->ignore($customer->id),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
        ]);

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
            $customer->teacher_name = $request->teacher_name;
        }


        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/customer/';
            $customer->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
        }

        $customer->save();
        return redirect()->route('users.customer-list')->with('success', 'Customer Data Updated successfully.');

    }


    public function customerDelete(Request $request, $id)
    {
        $customer = Customer::find($id);

        $directory = 'backend/images/customer';
        $filename = $customer->image;
        $this->deleteOne($directory, $filename);

        $customer->delete();

        return redirect()->route('users.customer-list')->with('success', 'Customer Data Deleted successfully.');
    }

    public function changeStatus($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return back()->with('error', 'Customer not found');
        }

        $customer->status = !$customer->status;
        $customer->save();

        return back()->with('success', 'Status changed successfully');
    }

    public function changeAgentStatus($id)
    {
        $agent = User::find($id);

        if (!$agent) {
            return back()->with('error', 'Agent not found');
        }

        $agent->status = !$agent->status;
        $agent->save();

        return back()->with('success', 'Status changed successfully');
    }



    public function addBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role != 3) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $user->balance += $request->amount;
        $user->save();


        PackageBalance::create([
            'amount' => $request->amount,
            'user_id' => $user->id,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Package balance added successfully.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required',
            'phone' => 'required|unique:users,phone'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->nid_number = $request->nid_number;
        $user->address = $request->address;
        $user->role = $request->role;
        if ($request->role == 3) {
            $user->balance = 1500;
        }
        $user->password = Hash::make('12345678');
        $user->created_by = Auth::user()->id;

        if (Auth::user()->role == 1) {
            $user->approved = 1;
        }

        if (Auth::user()->role == 1) {
            $user->status = 1;
        }

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/user/';
            $user->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
        }
        $user->save();

        if ($user->role == 3)
        {
            PackageBalance::create([
                'amount' => $user->balance,
                'user_id' => $user->id,
                'created_by' => Auth::user()->id
            ]);
        }


        if ($user->role == 1) {
            return redirect()->route('users.index')->with('success', 'Admin Data Created successfully.');
        }
        return redirect()->route('users.agent-list')->with('success', 'Agent/Incharge Data Created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('backend.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
        ]);


        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->nid_number = $request->nid_number;
        $user->address = $request->address;
        if ($user->role != 1)
        {
            $user->role = $request->role;
        }


        $user->created_by = Auth::user()->id;



        if ($request->hasFile('image')) {
            if ($user->image) {
                $this->deleteOne('backend/images/user/', $user->image);
            }

            $imageFile = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/user/';
            $user->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
        }

        $user->save();

        if ($user->role == 1) {
            return redirect()->route('users.index')->with('success', 'Admin Data Updated successfully.');
        }
        return redirect()->route('users.agent-list')->with('success', 'Agent/Incharge Data Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $directory = 'backend/images/user';
        $filename = $user->image;
        $this->deleteOne($directory, $filename);

        $user->delete();

        if ($user->role == 1) {
            return redirect()->route('users.index')->with('success', 'Admin Data Deleted successfully.');
        }
        return redirect()->route('users.agent-list')->with('success', 'Agent/Incharge Data Deleted successfully.');
    }
}
