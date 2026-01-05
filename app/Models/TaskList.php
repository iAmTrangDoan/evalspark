<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $casts = [
        'position' => 'integer',
    ];

    protected $fillable = [
        'group_id',
        'name',
        'position',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'list_id')->orderBy('position');
    }
}
