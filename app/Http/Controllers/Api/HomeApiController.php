<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Banner;
use App\Models\Customer;
use App\Models\Mission;
use App\Models\NewsFeedPost;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\VideoUrl;
use App\Models\Vision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class HomeApiController extends Controller
{
    public function getBanner()
    {
        try {

            // if (!Auth::check()) {
            //     return response()->json(['status' => false,'message' => 'Unauthorized'], 401);
            // }

            $banners = Banner::latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Banner list get successfully.',
                'banner_lists' => $banners->map(function ($banner) {
                    return [
                        'id' => $banner->id,
                        'title' => $banner->title,
                        'image' => asset('backend/images/banner/' . $banner->image) ?? '',
                    ];
                }),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving banner list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUrl()
    {
        try {

            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $urls = VideoUrl::latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Video url list get successfully.',
                'video_url_lists' => $urls->map(function ($url) {
                    return [
                        'id' => $url->id,
                        'url_link' => $url->url_link,
                    ];
                }),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving video list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMission()
    {
        try {
            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $mission = Mission::first();

            return response()->json([
                'status' => true,
                'message' => 'Mission Data get successfully.',
                'mission_data' => $mission,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving mission.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVision()
    {
        try {
            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $vision = Vision::first();

            return response()->json([
                'status' => true,
                'message' => 'Vision Data get successfully.',
                'vision_data' => $vision,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving vision.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAboutUs()
    {
        try {

            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $about = AboutUs::first();

            return response()->json([
                'status' => true,
                'message' => 'About Us Data get successfully.',
                'about_data' => $about,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving about us.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTerms()
    {
        try {
            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $terms = TermsCondition::first();

            return response()->json([
                'status' => true,
                'message' => 'Terms and Condition Data get successfully.',
                'terms_and_condition_data' => $terms,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving termsCondition.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPolicy()
    {
        try {
            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            $policy = PrivacyPolicy::first();

            return response()->json([
                'status' => true,
                'message' => 'Privacy Policy Data get successfully.',
                'privacy_policy_data' => $policy,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving privacy policy.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPost(Request $request)
    {
        try {
            // if (!Auth::check()) {
            //     return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            // }

            // $user = Auth::user()->id;
            // $customer = Customer::find($user);

            $perPage = $request->input('perPage', 50);
            $currentPage = max(1, $request->input('page', 1));

            $posts = NewsFeedPost::where('status', 1)->where('approved', 1)
                ->latest()
                ->paginate($perPage);

            $formattedPosts = $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'created_by' => $post->createdBy->name,
                    'created_by_image' => asset('backend/images/user/' . $post->createdBy->image),
                    'title' => $post->title,
                    'created_at' => $post->created_at->diffForHumans(),
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
}
