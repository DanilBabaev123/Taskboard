<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\BoardController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Teams
    Route::apiResource('teams', TeamController::class);
    Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember']);

    // Boards (nested resource)
    Route::apiResource('teams.boards', BoardController::class)->shallow();

    // Tasks (nested resource)
    Route::apiResource('boards.tasks', TaskController::class)->shallow();

    // Move task (drag-and-drop)
    Route::patch('/tasks/{task}/move', [TaskController::class, 'move']);

    Route::get('/teams/{team}/users', [TeamController::class, 'users']);
});
