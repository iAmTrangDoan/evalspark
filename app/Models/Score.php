<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'score';

    protected $fillable = [
        'group_id',
        'user_id',
        'lecture_id',
        'score',
        'comment',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Student
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecture_id'); // Lecturer
    }
}
