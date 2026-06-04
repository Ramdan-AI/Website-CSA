<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title',
        'description',
        'cover_image'
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
