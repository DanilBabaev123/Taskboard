<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index(Board $board)
    {
        $this->authorize('view', $board->team);
        return response()->json($board->tasks);
    }


    public function store(Request $request, Board $board)
    {
    $this->authorize('update', $board->team);
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
        'due_date' => 'nullable|date',
    ]);

        $task = $board->tasks()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date'    => $request->due_date,
            'status'      => Task::STATUS_READY,
            'created_by'  => Auth::id(),
            'order'       => Task::where('board_id', $board->id)->max('order') + 1,
        ]);

        return response()->json($task, 201);
    }


    public function show(Task $task)
    {
        $this->authorize('view', $task->board->team);
        return response()->json($task);
    }


    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task->board->team);
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'in:ready,in_progress,impossible',
            'impossible_reason' => 'nullable|string|max:1000',
        ]);

        $data = $request->only(['title', 'description', 'assigned_to', 'due_date', 'status']);
        if ($request->has('impossible_reason')) {
            $data['impossible_reason'] = $request->impossible_reason;
        }

        $task->update($data);
        return response()->json($task);
    }


    public function destroy(Task $task)
    {
        $this->authorize('delete', $task->board->team);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }


    public function move(Request $request, Task $task)
{
    $this->authorize('update', $task->board->team);
    $request->validate([
        'status' => 'required|in:ready,in_progress,impossible',
        'new_order' => 'required|integer|min:0',
    ]);

    $oldStatus = $task->status;
    $newStatus = $request->status;
    $newOrder = $request->new_order;

    Task::where('board_id', $task->board_id)
        ->where('status', $oldStatus)
        ->where('order', '>', $task->order)
        ->decrement('order');

    $task->status = $newStatus;
    $task->order = $newOrder;
    $task->save();

    Task::where('board_id', $task->board_id)
        ->where('status', $newStatus)
        ->where('order', '>=', $newOrder)
        ->where('id', '!=', $task->id)
        ->increment('order');

    return response()->json($task);
}
}
