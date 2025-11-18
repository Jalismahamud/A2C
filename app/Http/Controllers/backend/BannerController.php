<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    use ImageUploader;
    public function create()
    {
        return view('backend.banners.create');
    }

    public function index()
    {
        $banners = Banner::latest()->get();
        return view('backend.banners.index', compact('banners'));
    }

    public function store (Request $request)
    {
            $request->validate([
                'title' => 'required|string|max:100',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $banner = new Banner();
            $banner->title = $request->title;
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $width = 400;
                $height = 400;
                $folder = 'backend/images/banner/';
                $banner->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
            }

            $banner->save();

            return redirect(route('banner.index'))->with('success', 'Banner Data Created Successfully');

    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('backend.banners.edit', compact('banner'));
    }


    public function update (Request $request, $id)
    {
        $banner =  Banner::find($id);

        $request->validate([
            'title' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $banner->title = $request->title;
        if ($request->hasFile('image')) {
            if ($banner->image) {
                $this->deleteOne('backend/images/banner/', $banner->image);
            }

            $imageFile = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/banner/';
            $banner->image = $this->uploadImage($imageFile, $width, $height, $folder, 75);
        }

        $banner->save();

        return redirect(route('banner.index'))->with('success', 'Banner Data Updated Successfully');

    }


    public function delete(Request $request, $id)
    {
        $banner = Banner::find($id);

        $directory = 'backend/images/banner';
        $filename = $banner->image;
        $this->deleteOne($directory, $filename);

        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Banner Data Deleted successfully.');

    }
}
