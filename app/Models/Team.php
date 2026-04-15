<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Board;


class Team extends Model
{
    protected $fillable = ['name', 'owner_id'];
public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
public function users()
{
    return $this->belongsToMany(User::class)->withPivot('role');
}
public function boards()
{
    return $this->hasMany(Board::class);
}
}
