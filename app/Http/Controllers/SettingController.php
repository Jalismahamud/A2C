<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\ImageUploader;

class SettingController extends Controller
{
    use ImageUploader;

    public function index()
    {
        $settings = Setting::latest()->get();
        $setting = Setting::first();
        return view('backend.setting.index', compact('settings', 'setting'));
    }

    public function edit()
    {
        $setting = Setting::first();
        return view('backend.setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
    // dd($request->all());
        $request->validate([
            'registration_bonus' => 'required',
            'agent_minimum_withdraw' => 'required',
            'company_logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = Setting::find(1);

        $setting->company_name = $request->company_name ?? null;
        $setting->company_email = $request->company_email ?? null;
        $setting->company_phone = $request->company_phone ?? null;
        $setting->company_address = $request->company_address ?? null;
        if ($request->hasFile('company_logo')) {
            $imageFile = $request->file('company_logo');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/banner/';
            $setting->company_logo = $this->uploadImage($imageFile, $width, $height, $folder, 75);
        }
        $setting->registration_bonus = $request->registration_bonus ?? null;
        $setting->agent_minimum_withdraw = $request->agent_minimum_withdraw ?? null;

        $setting->save();

        return redirect(route('setting.index'))->with('success', 'BData Created Successfully');
    }
}
