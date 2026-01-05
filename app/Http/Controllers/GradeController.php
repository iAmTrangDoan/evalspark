<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Score;

class GradeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'grades' => 'required|array',
            'grades.*.score' => 'nullable|numeric|min:0|max:10',
            'grades.*.feedback' => 'nullable|string',
        ]);

        $group = Group::findOrFail($request->group_id);

        if ($group->classRoom->lecture_id !== Auth::id()) {
            abort(403, 'Only the lecturer can grade this group.');
        }

        // Save scores to Score table
        foreach ($request->grades as $userId => $data) {
            Score::updateOrCreate(
                [
                    'group_id' => $group->id,
                    'user_id' => $userId,
                ],
                [
                    'lecture_id' => Auth::id(),
                    'score' => $data['score'],
                    'comment' => $data['feedback'],
                ]
            );
        }

        // Lock the group only if requested
        if ($request->has('lock') && $request->lock) {
            $group->update(['is_graded' => true]);
            return back()->with('success', 'Grades submitted and locked successfully.');
        }

        return back()->with('success', 'Grades saved successfully.');
    }
    
    // Optional: Add unlock method if needed later
    public function unlock(Request $request, Group $group) {
         if ($group->classRoom->lecture_id !== Auth::id()) {
            abort(403);
        }
        $group->update(['is_graded' => false]);
        return back()->with('success', 'Grading unlocked.');
    }
}
