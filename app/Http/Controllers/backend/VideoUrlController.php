<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\VideoUrl;
use Illuminate\Http\Request;

class VideoUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $urls = VideoUrl::latest()->get();
        return view('backend.url.index', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.url.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'url_link' => 'required|string',
        ]);

        $url = new VideoUrl();
        $url->url_link = $request->url_link;
        $url->save();

        return redirect(route('video-url.index'))->with('success', 'Url Created Successfully');
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
        $url = VideoUrl::find($id);
        return view('backend.url.edit', compact('url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $url =  VideoUrl::find($id);

        $request->validate([
            'url_link' => 'required|string',
        ]);

        $url->url_link = $request->url_link;
        $url->save();

        return redirect(route('video-url.index'))->with('success', 'Url Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $url = VideoUrl::find($id);
        $url->delete();

        return redirect()->route('video-url.index')->with('success', 'Url Deleted successfully.');
    }
}
