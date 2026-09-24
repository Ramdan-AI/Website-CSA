<?php

namespace App\Models;

use App\Support\LogsActivity;
use App\Support\ResolvesStorageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Minute extends Model
{
    use LogsActivity;
    use SoftDeletes;
    use ResolvesStorageUrl;

    protected $fillable = [
        'created_by',
        'title',
        'slug',
        'meeting_date',
        'location',
        'agenda',
        'decisions',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Minute $minute) {
            $minute->slug = static::uniqueSlug($minute->title);
        });

        static::updating(function (Minute $minute) {
            if ($minute->isDirty('title')) {
                $minute->slug = static::uniqueSlug($minute->title, $minute->id);
            }
        });
    }

    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(Member::class, 'minute_member')->withTimestamps();
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->storageUrl($this->attachment);
    }
}
