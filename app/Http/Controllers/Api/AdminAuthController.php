<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\PackageBalance;
use App\Models\User;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class AdminAuthController extends Controller
{
    use ImageUploader;
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $admin = User::where('phone', $request->phone)->where('role', 1)->where('status', 1)->where('approved', 1)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('AdminToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'id' => $admin->id,
            'name' => $admin->name,
            'phone' => $admin->phone,
            'role' => $admin->role,
            'status' => $admin->status,
            'approved' => $admin->approved,
            'created_at' => $admin->created_at

        ], 200);
    }


    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $user = new User();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->nid_number = $request->nid_number;
            $user->address = $request->address;
            $user->role = $request->role;
            $user->password = Hash::make('12345678');
            $user->created_by = Auth::user()->id;

            if (Auth::user()->role == 1) {
                $user->approved = 1;
                $user->status = 1;
            }

            if ($request->role == 3) {
                $user->balance = 1500;
            }

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $user->image = $this->uploadImage($imageFile, 400, 400, 'backend/images/user/', 75);
            }

            DB::beginTransaction();
            $user->save();

            if ($user->role == 3) {
                PackageBalance::create([
                    'amount' => $user->balance,
                    'user_id' => $user->id,
                    'created_by' => Auth::user()->id,
                ]);
            }
            DB::commit();

            return response()->json(['status' => true, 'message' => 'User created successfully.', 'user' => new UserResource($user)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error occurred while creating user', 'error' => $e->getMessage()], 500);
        }
    }

    public function adminList(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $admins = User::where('role', 1)->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Admin list get successfully.',
                'admin_lists' => UserResource::collection($admins),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving admin list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function userInactive(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $admins = User::where('status', 0)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Inactive User get successfully.',
                'inactive_user_lists' => UserResource::collection($admins),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving admin list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function statusChange($id): JsonResponse
    {
        try {

            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }


            $admin = User::whereNot('id', 1)->find($id);


            if (!$admin) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 404);
            }


            if ($admin->status == 0) {
                $admin->status = 1;
                $admin->save();
            } else {
                $admin->status = 0;
                $admin->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Status Changed successfully.',
                'user' => new UserResource($admin),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function agentInchargeList(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $admins = User::whereIn('role', [2, 3])->where('status', 1)->where('approved', 1)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Agent InCharge list get successfully.',
                'agent_incharge_list' => UserResource::collection($admins),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving admin list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requestAgentList(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $admins = User::where('role', 3)->where('status', 1)->where('approved', 0)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Requested Agent list get successfully.',
                'request_agent_list' => UserResource::collection($admins),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving admin list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requestAgentApproved($id): JsonResponse
    {
        try {

            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }


            $admin = User::where('role', 3)
                ->where('status', 1)
                ->where('approved', 0)
                ->find($id);

            if (!$admin) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 404);
            }


            $admin->approved = 1;
            $admin->save();

            return response()->json([
                'status' => true,
                'message' => 'Requested agent approved successfully.',
                'approved_request_agent' => new UserResource($admin),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $user = User::find($id);
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->nid_number = $request->nid_number;
            $user->address = $request->address;
            $user->created_by = Auth::user()->id;

            if ($request->hasFile('image')) {
                if ($user->image) {
                    $this->deleteOne('backend/images/user/', $user->image);
                }

                $imageFile = $request->file('image');
                $user->image = $this->uploadImage($imageFile, 400, 400, 'backend/images/user/', 75);
            }

            $user->save();

            return response()->json(['status' => true, 'message' => 'User updated successfully.', 'user' => new UserResource($user)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while updating user', 'error' => $e->getMessage()], 500);
        }
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if ($token) {
            if ($user->role == 1) {
                $token->delete();
                return response()->json(['message' => 'Admin successfully logged out'], 200);
            }
        }

        return response()->json(['message' => 'Invalid token'], 401);
    }


    public function checkUrl(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['role' => $user->role]);
    }
}
