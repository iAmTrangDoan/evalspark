<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'class_id',
        'leader_id',
        'name',
        'description',
        'status',
        'is_public',
        'is_graded', // Added
    ];

    protected $casts = [
        'is_graded' => 'boolean',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id')
                    ->withPivot(['role', 'grade_score', 'grade_feedback']);
    }

    public function lists()
    {
        return $this->hasMany(TaskList::class, 'group_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'group_id');
    }
}
