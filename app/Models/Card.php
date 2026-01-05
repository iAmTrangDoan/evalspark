<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    public $timestamps = true;
    const UPDATED_AT = null;


    protected $fillable = [
        'list_id',
        'name',
        'description',
        'assigned_to', // user_id
        'priority',
        'position',
        'due_date',
        'reminder_date',
        'is_completed',
        'is_active',
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'card_id');
    }
}
