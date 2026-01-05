<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function store(Request $request)
    {
         $request->validate([
            'card_id' => 'required|exists:cards,id',
            'name' => 'required|string|max:255',
        ]);

        $checklist = Checklist::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Checklist added.', 'checklist' => $checklist]);
        }
        return back()->with('success', 'Checklist added.');
    }

    public function update(Request $request, Checklist $checklist)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $checklist->update(['name' => $request->name]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Checklist updated.', 'checklist' => $checklist]);
        }
        return back()->with('success', 'Checklist updated.');
    }

    public function destroy(Request $request, Checklist $checklist)
    {
        $checklist->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Checklist deleted.']);
        }
        return back()->with('success', 'Checklist deleted.');
    }
}
