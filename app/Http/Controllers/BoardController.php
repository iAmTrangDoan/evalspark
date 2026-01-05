<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // Lists
    public function storeList(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);

        TaskList::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'position' => 0, // Logic for position needed if DnD
        ]);

        return back()->with('success', 'List created.');
    }

    // Cards
    public function storeCard(Request $request)
    {
        $request->validate([
            'list_id' => 'required|exists:lists,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        Card::create([
            'list_id' => $request->list_id,
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'position' => 0,
        ]);

        return back()->with('success', 'Card created.');
    }

    // Update Card (Move, Edit) - basic implementation
    public function updateCard(Request $request, Card $card)
    {
        $card->update($request->all());
        return back()->with('success', 'Card updated.');
    }

    public function storeChecklist(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'name' => 'required|string|max:255',
        ]);

        \App\Models\Checklist::create($request->all());
        return back()->with('success', 'Checklist added.');
    }

    public function storeChecklistItem(Request $request)
    {
        $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'content' => 'required|string|max:255',
        ]);

        \App\Models\ChecklistItem::create([
            'checklist_id' => $request->checklist_id,
            'content' => $request->content,
            'is_checked' => false,
        ]);
        return back()->with('success', 'Item added.');
    }

    public function toggleChecklistItem(\App\Models\ChecklistItem $item)
    {
        $item->update(['is_checked' => !$item->is_checked]);
        return back();
    }
}
