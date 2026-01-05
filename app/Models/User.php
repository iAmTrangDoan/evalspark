<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ClassRoom; // Added for relationships
use App\Models\Group;     // Added for relationships
use App\Models\Card;      // Added for relationships

class User extends Authenticatable implements MustVerifyEmail // Added implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code', // Added
        'email',
        'password',
        'role',
        'avatar', // Added
        'is_active', // Added
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // Added
        ];
    }

    // Relationships

    // Classes created by Lecturer
    public function teachingClasses()
    {
        return $this->hasMany(ClassRoom::class, 'lecture_id');
    }

    // Classes joined by Student
    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_members', 'user_id', 'class_id');
    }

    // Groups the user belongs to
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id')
                    ->withPivot(['role', 'grade_score', 'grade_feedback']);
    }

    // Tasks assigned to user
    public function tasks()
    {
        return $this->hasMany(Card::class, 'assigned_to');
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }
}
