<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Traits\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    use ImageUploader;
    public function edit(Request $request): View
    {
        return view('backend.profile.index', [
            'user' => User::find(Auth::user()->id),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $auth_id = Auth::user()->id;
        $user = User::where('id', $auth_id)->first();
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('image')) {
            if ($user->image) {
                $this->deleteOne('backend/images/user/', $user->image);
            }

            $imageFile = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/user/';
            $user->image = $this->uploadImage($imageFile, $width, $height, $folder);
        }

        $user->save();
        return redirect()->back()->with('success', 'Profile updated Successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);


        $user = Auth::guard('web')->user();


        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->new_password);
            $user->save();


            return redirect()->back()->with('success', 'Your Password Updated Successfully.');
        }

        return redirect()->back()->with('error', 'Current Password does not match');
    }
}
