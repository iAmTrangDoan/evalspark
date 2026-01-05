<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
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

    public function update(Request $request, Card $card)
    {
        $input = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|integer|in:0,1,2,3',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'is_completed' => 'boolean'
        ]);

        $card->update($input);

        if($request->wantsJson()) {
            return response()->json(['message' => 'Card updated successfully', 'card' => $card]);
        }

        return back()->with('success', 'Card updated.');
    }

    public function show(Card $card)
    {
        return $card; // Or view
    }
    public function reorder(Request $request)
    {
        $request->validate([
            'list_id' => 'required|exists:lists,id',
            'cards' => 'required|array',
            'cards.*' => 'exists:cards,id',
        ]);

        foreach ($request->cards as $position => $cardId) {
            Card::where('id', $cardId)->update([
                'list_id' => $request->list_id,
                'position' => $position + 1, // 1-based index
            ]);
        }

        return response()->json(['message' => 'Cards reordered.']);
    }
    public function destroy(Request $request, Card $card)
    {
        $card->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Card deleted.']);
        }

        return back()->with('success', 'Card deleted.');
    }
}
