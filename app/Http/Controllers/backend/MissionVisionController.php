<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Vision;
use Illuminate\Http\Request;

class MissionVisionController extends Controller
{
    public function editMission($id)
    {
        $mission = Mission::find($id);
        return view('backend.mission.index', compact('mission'));
    }

    public function updateMission(Request $request, $id)
    {
        $mission = Mission::find($id);
        if ($mission) {

            $mission->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

        }

        return redirect()->back()->with('success', 'updated successfully');

    }


    public function editVision($id)
    {
        $vision = Vision::find($id);
        return view('backend.vision.index', compact('vision'));
    }

    public function updateVision(Request $request, $id)
    {
        $vision = Vision::find($id);

        if ($vision) {

            $vision->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

        }

        return redirect()->back()->with('success', 'updated successfully');

    }
}
