<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'content',
        'thumbnail',
        'images',
        'status',
        'published_at'
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'images' => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($news) {
            $news->slug = static::generateUniqueSlug($news->title);
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = static::generateUniqueSlug($news->title, $news->id);
            }
        });
    }

    protected static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

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

    public function getThumbnailUrlAttribute()
    {
        $firstImage = $this->thumbnail ?? $this->images[0] ?? null;

        return $firstImage ? asset('storage/' . $firstImage) : null;
    }

    /**
     * Short plain-text teaser for listing cards. Returns null when the
     * content column was not selected (list queries stay lightweight).
     */
    public function getExcerptAttribute(): ?string
    {
        if (!array_key_exists('content', $this->attributes) || !$this->attributes['content']) {
            return null;
        }

        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->attributes['content']))), 120);
    }
}
