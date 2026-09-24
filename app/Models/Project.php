<?php

namespace App\Models;

use App\Support\LogsActivity;
use App\Support\ResolvesStorageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use LogsActivity;
    use SoftDeletes;
    use ResolvesStorageUrl;

    protected $fillable = [
        'submitted_by',
        'title',
        'slug',
        'summary',
        'description',
        'thumbnail',
        'images',
        'tech_stack',
        'repo_url',
        'demo_url',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'tech_stack' => 'array',
            'featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            $project->slug = static::generateUniqueSlug($project->title);
        });

        static::updating(function (Project $project) {
            if ($project->isDirty('title')) {
                $project->slug = static::generateUniqueSlug($project->title, $project->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
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

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'project_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $first = $this->thumbnail ?? ($this->images[0] ?? null);

        return $this->storageUrl($first);
    }

    /**
     * Ringkasan pendek untuk kartu di daftar proyek.
     */
    public function getExcerptAttribute(): ?string
    {
        if ($this->summary) {
            return $this->summary;
        }

        if (!array_key_exists('description', $this->attributes) || !$this->attributes['description']) {
            return null;
        }

        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->attributes['description']))), 140);
    }

    /**
     * Nama anggota tim, atau pengunggah bila proyek dikerjakan sendiri.
     */
    public function getContributorsAttribute(): string
    {
        if ($this->relationLoaded('members') && $this->members->isNotEmpty()) {
            return $this->members->pluck('name')->take(3)->join(', ')
                . ($this->members->count() > 3 ? ' +' . ($this->members->count() - 3) : '');
        }

        return $this->submitter?->name ?? 'CSA';
    }
}
