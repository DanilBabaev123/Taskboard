<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Task;

class Board extends Model
{
    protected $fillable = ['name', 'team_id'];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
