<?php

namespace App\Http\Controllers\backend\incharge;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InchargeAgentController extends Controller
{
    public function index()
    {
        $totalAgent = User::where('status', 1)->where('role', 2)->where('created_by', Auth::user()->id)->count();
        $todaysAgent = User::where('status', 1)->where('role', 2)->where('created_by', Auth::user()->id)->whereDate('created_at', Carbon::today())->count();

        return view('backend.incharge.agent.index', compact('totalAgent', 'todaysAgent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.incharge.agent.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'phone' => [
                'required',
                Rule::unique('users', 'phone'),
            ],
            'nid_number' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
        ]);

        try {
            DB::beginTransaction();

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
                ->route('incharge.agents.index')
                ->with('success', 'Agent created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Agent Create Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $agent = User::where('id', $id)->where('role', 2)->where('created_by', Auth::user()->id)->firstOrFail();

        return view('backend.incharge.agent.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($id),
            ],
            'nid_number' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('id', $id)->where('role', 2)->where('created_by', Auth::user()->id)->firstOrFail();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->nid_number = $request->nid_number;
            $user->approved = $request->approved ?? $user->approved;
            $user->status = $request->status ?? $user->status;

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
                ->route('incharge.agents.index')
                ->with('success', 'Agent updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Agent Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 2)->where('created_by', Auth::user()->id)->firstOrFail();

            if ($user->image && file_exists(public_path('backend/images/user/' . $user->image))) {
                unlink(public_path('backend/images/user/' . $user->image));
            }

            $user->delete();

            return response()->json(['message' => 'Agent deleted successfully.'], 200);
        } catch (\Throwable $e) {
            Log::error('Agent Delete Error: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }
}
