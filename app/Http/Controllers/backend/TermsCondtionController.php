<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsCondtionController extends Controller
{
    public function editTerms($id)
    {
        $term = TermsCondition::find($id);
        return view('backend.terms.index', compact('term'));
    }

    public function updateTerms(Request $request, $id)
    {
        $term = TermsCondition::find($id);
        if ($term) {

            $term->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
            ]);

        }

        return redirect()->back()->with('success', 'updated successfully');

    }

    public function editPolicy($id)
    {
        $policy = PrivacyPolicy::find($id);
        return view('backend.policy.index', compact('policy'));
    }

    public function updatePolicy(Request $request, $id)
    {
        $policy = PrivacyPolicy::find($id);
        if ($policy) {

            $policy->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
            ]);

        }

        return redirect()->back()->with('success', 'updated successfully');

    }

}
