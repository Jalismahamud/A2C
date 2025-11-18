<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\NewsFeedPost;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsFeedPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ImageUploader;
    public function index(Request $request)
    {
        $postsQuery = NewsFeedPost::query();

        $perPage = isset($request['perPage']) ? intval($request['perPage']) : 100;
        $currentPage = max(1, $request->input('page', 1));
        $startIndex = ($currentPage - 1) * $perPage;
        $posts =  $postsQuery->latest()->where('approved', 1)->paginate($perPage);

        if ($request->ajax()) {
            return view('backend.posts.index_table', compact('posts', 'startIndex'))->render();
        } else {
            return view('backend.posts.index', compact('posts', 'startIndex'));
        }

    }

    public function requestList()
    {
        $posts =  NewsFeedPost::latest()->where('approved', 0)->get();
        return view('backend.posts.request_list', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
        ]);


           $newsPost = new NewsFeedPost();
           $newsPost->title = $request->title;
           $newsPost->approved = 1;
           $newsPost->status = 1;
           $newsPost->created_by = Auth::user()->id;

        if ($request->hasFile('image')) {
            $images = $request->file('image');
            $width = 400;
            $height = 400;
            $folder = 'backend/images/news-feed-post/';
            $uploadedImages = [];

            foreach ($images as $imageFile) {

                $uploadedImages[] = $this->uploadImage($imageFile, $width, $height, $folder, 75);

            }
            $newsPost->image = json_encode($uploadedImages);
        }


        $newsPost->save();
        return redirect()->route('posts.index')->with('success', 'Post Data Created successfully.');

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
        $post = NewsFeedPost::find($id);
        return view('backend.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
        ]);

        $newsPost = NewsFeedPost::findOrFail($id);
        $newsPost->title = $request->title;
        $newsPost->approved = 1;
        $newsPost->status = 1;
        $newsPost->created_by = Auth::user()->id;

        if ($request->hasFile('image')) {
            $width = 400;
            $height = 400;
            $folder = 'backend/images/news-feed-post/';
            $uploadedImages = [];


            $existingImages = json_decode($newsPost->image, true) ?? [];
            if (!empty($existingImages)) {
                foreach ($existingImages as $oldImage) {
                    $this->deleteOne($folder, $oldImage);
                }
            }

            foreach ($request->file('image') as $imageFile) {
                $uploadedImages[] = $this->uploadImage($imageFile, $width, $height, $folder, 75);
            }

            $newsPost->image = json_encode($uploadedImages);
        }

        $newsPost->save();

        return redirect()->route('posts.index')->with('success', 'Post Data Updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = NewsFeedPost::find($id);

        if ($post) {

            if (!empty($post->image)) {
                $images = json_decode($post->image, true);

                foreach ($images as $image) {
                    $imagePath = public_path('backend/images/news-feed-post/' . $image);

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            $post->delete();

            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        }

        return redirect()->route('posts.index')->with('error', 'Post not found.');
    }


    public function changeStatus($id)
    {
        $post = NewsFeedPost::find($id);

        if (!$post) {
            return back()->with('error', 'Agent not found');
        }

        $post->status = !$post->status;
        $post->save();

        return back()->with('success', 'Status changed successfully');
    }

    public function approvePost($id)
    {
        $post = NewsFeedPost::find($id);

        if (!$post) {
            return back()->with('error', 'Post not found');
        }

        $post->approved = !$post->approved;
        $post->save();

        return back()->with('success', 'Approved successfully');
    }

}
