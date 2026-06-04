<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(
            NewsCategory::class,
            'category_id'
        );
    }

    public function author()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
