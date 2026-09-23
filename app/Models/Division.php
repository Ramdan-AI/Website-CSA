<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Division extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'skills',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'skills' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($division) {
            $division->slug = static::generateUniqueSlug($division->name);
        });

        static::updating(function ($division) {
            if ($division->isDirty('name')) {
                $division->slug = static::generateUniqueSlug($division->name, $division->id);
            }
        });

        static::deleting(function ($division) {
            if ($division->isForceDeleting() && $division->image) {
                Storage::disk('public')->delete($division->image);
            }
        });
    }

    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->whereNull('deleted_at')
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

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->image);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
