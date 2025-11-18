<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function editContact($id)
    {
        $about = AboutUs::find($id);
        return view('backend.about.index', compact('about'));
    }

    public function updateContact(Request $request, $id)
    {
        $about = AboutUs::find($id);
        if ($about) {

            $about->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'facebook' => $request->facebook,
                'whatsapp_contact' => $request->whatsapp_contact,
            ]);

        }

        return redirect()->back()->with('success', 'updated successfully');

    }
}
