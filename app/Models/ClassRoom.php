<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'lecture_id',
        'name',
        'join_code',
        'semester',
        'description',
        'status',
        'class_code', // Add class_code here
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecture_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_members', 'class_id', 'user_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'class_id');
    }
}
