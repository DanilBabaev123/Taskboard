<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams;
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $team = Team::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ]);
        $team->users()->attach(Auth::id(), ['role' => 'admin']);
        return response()->json($team, 201);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);
        return response()->json($team->load('users', 'boards'));
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        $request->validate(['name' => 'required|string|max:255']);
        $team->update(['name' => $request->name]);
        return response()->json($team);
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $team->delete();
        return response()->json(['message' => 'Team deleted']);
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('addMember', $team);
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot add yourself'], 422);
        }

        if ($team->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User already in team'], 422);
        }

        $team->users()->attach($user, ['role' => 'member']);
        return response()->json(['message' => 'Member added']);
    }
    public function users(Team $team)
    {
        $this->authorize('view', $team);
        return response()->json($team->users);
    }
}
