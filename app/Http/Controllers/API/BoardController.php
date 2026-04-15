<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Team;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function index(Team $team)
    {
        $this->authorize('view', $team);
        return response()->json($team->boards);
    }


    public function store(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $board = $team->boards()->create([
            'name' => $request->name,
        ]);

        return response()->json($board, 201);
    }


    public function show(Board $board)
    {
        $this->authorize('view', $board->team);
        return response()->json($board->load('tasks', 'team'));
    }


    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board->team);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $board->update(['name' => $request->name]);

        return response()->json($board);
    }


    public function destroy(Board $board)
    {
        $this->authorize('delete', $board->team);
        $board->delete();
        return response()->json(['message' => 'Board deleted']);
    }
}
