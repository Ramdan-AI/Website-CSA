<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'album_id',
        'image',
        'caption'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
