<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    const STATUS_READY = 'ready';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_IMPOSSIBLE = 'impossible';


    protected $fillable = [
        'title',
        'description',
        'status',
        'board_id',
        'assigned_to',
        'created_by',
        'due_date',
        'order',
        'impossible_reason',
    ];


    protected $casts = [
        'due_date' => 'date',
        'order' => 'integer',
    ];




    public function board()
    {
        return $this->belongsTo(Board::class);
    }


    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }





    public function canChangeStatusTo($newStatus)
    {
        return in_array($newStatus, [
            self::STATUS_READY,
            self::STATUS_IN_PROGRESS,
            self::STATUS_IMPOSSIBLE,
        ]);
    }


    public function isImpossible()
    {
        return $this->status === self::STATUS_IMPOSSIBLE;
    }
}
