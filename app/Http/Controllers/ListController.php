<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);

        TaskList::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'position' => 0, // Should calculate max position + 1
        ]);

        return back()->with('success', 'List created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list)
    {
        $request->validate([
            'name' => 'string|max:255',
        ]);

        $list->update($request->only('name'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'List updated.', 'list' => $list]);
        }

        return back()->with('success', 'List updated.');
    }
    public function reorder(Request $request)
    {
        $request->validate([
            'lists' => 'required|array',
            'lists.*' => 'exists:lists,id',
        ]);

        foreach ($request->lists as $position => $listId) {
             TaskList::where('id', $listId)->update(['position' => $position + 1]);
        }

        return response()->json(['message' => 'Lists reordered.']);
    }
    public function destroy(Request $request, TaskList $list)
    {
        $list->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'List deleted.']);
        }

        return back()->with('success', 'List deleted.');
    }
}
