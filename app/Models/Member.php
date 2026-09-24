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
    use ResolvesStorageUrl;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'division_id',
        'position_id',
        'name',
        'slug',
        'nim',
        'semester',
        'level',
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
        'semester' => 'integer',
        'level' => 'integer',
    ];

    /**
     * Tingkatan skill anggota. Hanya dipakai divisi bertingkat.
     */
    public const LEVELS = [
        1 => ['label' => 'Tingkat 1', 'name' => 'Pemula', 'description' => 'Belum mengerti dasar atau baru sedikit mengerti dasar pemrograman.'],
        2 => ['label' => 'Tingkat 2', 'name' => 'Menengah', 'description' => 'Sudah mengerti basic pemrograman dan mampu mengerjakan hal-hal dasar.'],
        3 => ['label' => 'Tingkat 3', 'name' => 'Expert', 'description' => 'Sudah mahir (expert) dan mampu mengerjakan proyek secara mandiri.'],
    ];

    public function getLevelNameAttribute(): ?string
    {
        return $this->level ? (self::LEVELS[$this->level]['name'] ?? null) : null;
    }

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
            if ($member->isForceDeleting()) {
                if ($member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }

                return;
            }

            // Soft delete: bebaskan email & NIM agar tidak menahan unique index
            // dan bisa dipakai anggota baru. Dilakukan lewat query builder agar
            // tidak memicu event model lain (menghindari rekursi).
            static::withTrashed()
                ->whereKey($member->getKey())
                ->update([
                    'email' => null,
                    'nim' => 'deleted-'.$member->getKey(),
                ]);

            $member->setRawAttributes(array_merge(
                $member->getAttributes(),
                ['email' => null, 'nim' => 'deleted-'.$member->getKey()],
            ), true);
        });
    }

    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Unique index mencakup baris yang di-soft delete, jadi keberadaannya
        // juga harus dihitung agar tidak bentrok dengan constraint DB.
        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug.'-'.$counter;
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
