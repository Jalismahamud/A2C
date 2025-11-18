<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentInchargeLoginController extends Controller
{
    use ImageUploader;
    public function agentInchargeLogin(Request $request)
    {

        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);


        $user = User::where('phone', $request->phone)
            ->whereIn('role', [2,3])
            ->where('status', 1)
            ->where('approved', 1)
            ->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }


        $token = $user->createToken('AgentInChargeToken')->plainTextToken;


        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'image' => $user->image ? asset('backend/images/user/'.$user->image) : '',
            'role' => $user->role,
            'status' => $user->status,
            'approved' => $user->approved,
            'created_at' => $user->created_at
        ], 200);
    }





    public function getProfile()
    {

        if (!auth()->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if (!in_array($user->role, [2, 3])) {
        //if ($user->role != 3) {
            return response()->json(['message' => 'Forbidden: Insufficient permissions'], 403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'role' => $user->role,
            'status' => $user->status,
            'approved' => $user->approved,
            'image' =>$user->image ? asset('backend/images/user/'.$user->image) : '',

            //'image' => asset('backend/images/user/'.$user->image) ?? '',
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }


    public function updateProfile(Request $request)
    {

        if (!auth()->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $user = auth()->user();
       // dd($request->all());


        if (!in_array($user->role, [2, 3])) {
            return response()->json(['message' => 'Forbidden: Insufficient permissions'], 403);
        }


        $existingUser = User::where('phone', $request->phone)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return response()->json([
                'message' => 'The phone number has already been taken by another user.'
            ], 422);
        }


        if (isset($request->image)) {
            $imageDirectory = 'backend/images/user/';
            $imageFilename = basename($user->image);

            $this->deleteOne($imageDirectory, $imageFilename);

            $file = $request->image;
            $filename = $this->uploadImage($file, 300, 300, 'backend/images/user/', true);
            $image = $filename;
        } else {
            $image = $user->image ?? '';
        }


        $user->update([
            'name' => ucfirst($request->name),
            'phone' => $request->phone,
            'address' => $request->address,
            'nid_number' => $request->nid_number,
            'image' => $image,
        ]);


        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'role' => $user->role,
            'status' => $user->status,
            'approved' => $user->approved,
            'image' =>$user->image ? asset('backend/images/user/'.$user->image) : '',
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }




    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if ($token) {

            if ($user->role == 2 || $user->role == 3) {
                $token->delete();
                return response()->json(['message' => 'Agent / InCharge successfully logged out'], 200);
            }
        }

        return response()->json(['message' => 'Invalid token'], 401);

    }
}
