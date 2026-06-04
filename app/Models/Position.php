<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
