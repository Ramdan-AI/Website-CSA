<?php

namespace App\Models;

use App\Support\LogsActivity;
use App\Support\ResolvesStorageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Member extends Model
{
    use LogsActivity;
    use SoftDeletes;
    use ResolvesStorageUrl;

    protected $fillable = [
        'user_id',
        'division_id',
        'position_id',
        'name',
        'slug',
        'nim',
        'email',
        'photo',
        'bio',
        'phone',
        'linkedin',
        'instagram',
        'github',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($member) {
            $member->slug = static::generateUniqueSlug($member->name);
        });

        static::updating(function ($member) {
            if ($member->isDirty('name')) {
                $member->slug = static::generateUniqueSlug($member->name, $member->id);
            }
        });

        static::deleting(function ($member) {
            if ($member->isForceDeleting() && $member->photo) {
                Storage::disk('public')->delete($member->photo);
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
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->storageUrl($this->photo);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
