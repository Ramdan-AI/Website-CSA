<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'description',
        'max_members',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }
}
