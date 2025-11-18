<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsFeedPost;
use App\Traits\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class NewsFeedPostApiController extends Controller
{
    use ImageUploader;
    public function store(Request $request)
    {
        try {
            if (!auth()->user()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }


            $user = auth()->user();


            if (!in_array($user->role, [2, 3])) {
                return response()->json(['message' => 'Forbidden: Insufficient permissions'], 403);
            }

            $request->validate([
                'title' => 'required|string|max:150',
            ]);

            $newsPost = new NewsFeedPost();
            $newsPost->title = $request->title;
            $newsPost->approved = 0;
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

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully.',
                'data' => [
                    'title' => $newsPost->title,
                    'approved' => $newsPost->approved,
                    'status' => $newsPost->status,
                    'created_by' => $newsPost->created_by,
                    'created_at' => $newsPost->created_at,
                    'id' => $newsPost->id,
                    'images' => !empty($newsPost->image) ? collect(json_decode($newsPost->image, true))->map(function ($img) {
                        return asset('backend/images/news-feed-post/' . $img);
                    })->toArray() : [],
                    ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the post.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function postList(Request $request)
    {
        try {
            if (!auth()->user()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }


            $user = auth()->user();


            if (!in_array($user->role, [2, 3])) {
                return response()->json(['message' => 'Forbidden: Insufficient permissions'], 403);
            }
            \Log::info('Auth User:', ['user' => Auth::user()]);

            $perPage = $request->input('perPage', 50);
            $currentPage = max(1, $request->input('page', 1));

            $posts = NewsFeedPost::where('created_by', Auth::id())
                ->latest()
                ->paginate($perPage);


            $formattedPosts = $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'created_at' => $post->created_at->diffForHumans(),
                    'status' => $post->status == 1 ? 'Active' : 'Inactive',
                    'approved' => $post->approved == 1 ? 'Approved': 'Not Approved',
                    'images' => !empty($post->image) ? collect(json_decode($post->image, true))->map(function ($img) {
                        return asset('backend/images/news-feed-post/' . $img);
                    })->toArray() : [],
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Posts retrieved successfully.',
                'data' => [
                    'current_page' => $posts->currentPage(),
                    'total' => $posts->total(),
                    'per_page' => $posts->perPage(),
                    'last_page' => $posts->lastPage(),
                    'posts' => $formattedPosts,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Post Fetch Error:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching posts.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function update(Request $request,  $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:150',
            ]);

            $newsPost = NewsFeedPost::where('created_by', Auth::user()->id)->findOrFail($id);
            $newsPost->title = $request->title;

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

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully.',
                'data' => [
                    'title' => $newsPost->title,
                    'approved' => $newsPost->approved,
                    'status' => $newsPost->status,
                    'created_by' => $newsPost->created_by,
                    'created_at' => $newsPost->created_at,
                    'id' => $newsPost->id,
                    'images' => !empty($newsPost->image) ? collect(json_decode($newsPost->image, true))->map(function ($img) {
                        return asset('backend/images/news-feed-post/' . $img);
                    })->toArray() : [],
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the post.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function delete($id)
    {
        try {
            $post = NewsFeedPost::where('created_by', Auth::user()->id)->find($id);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found.',
                ], 404);
            }

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

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Post Deletion Error:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the post.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



}
