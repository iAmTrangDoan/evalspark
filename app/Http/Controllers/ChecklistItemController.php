<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'name' => 'required|string|max:255',
        ]);

        $maxPosition = ChecklistItem::where('checklist_id', $request->checklist_id)->max('position');
        
        $item = ChecklistItem::create([
            'checklist_id' => $request->checklist_id,
            'name' => $request->name,
            'is_checked' => false,
            'position' => $maxPosition ? $maxPosition + 1 : 1,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Item added.', 'item' => $item]);
        }
        return back()->with('success', 'Item added.');
    }

    public function update(Request $request, ChecklistItem $checklistItem)
    {
        // Toggle or update
        if ($request->has('is_checked')) {
             $checklistItem->update(['is_checked' => $request->boolean('is_checked')]);
        }
        if ($request->has('name')) {
            $request->validate(['name' => 'required|string|max:255']);
            $checklistItem->update(['name' => $request->name]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Item updated.', 'item' => $checklistItem]);
        }
        return back();
    }

    public function destroy(Request $request, ChecklistItem $checklistItem)
    {
        $checklistItem->delete();
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Item deleted.']);
        }
        return back()->with('success', 'Item deleted.');
    }
}
