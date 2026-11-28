<?php

namespace App\Models;

use App\Support\FlushesQueryCache;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use FlushesQueryCache;

    protected $fillable = [
        'album_id',
        'image',
        'caption',
        'is_hero'
    ];

    protected function casts(): array
    {
        return [
            'is_hero' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}