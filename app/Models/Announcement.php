<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public const CATEGORY_INFO = 'info';
    public const CATEGORY_IMPORTANT = 'penting';
    public const CATEGORY_URGENT = 'urgent';

    protected $fillable = [
        'created_by',
        'title',
        'body',
        'category',
        'pinned',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'pinned' => 'boolean',
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Pengumuman yang layak tampil: sudah terbit dan berada dalam
     * rentang tanggal (bila rentangnya diisi).
     */
    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query
            ->where('status', 'published')
            ->where(fn ($sub) => $sub->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn ($sub) => $sub->whereNull('ends_at')->orWhere('ends_at', '>=', $today));
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('created_at');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_URGENT => 'Penting Sekali',
            self::CATEGORY_IMPORTANT => 'Penting',
            default => 'Informasi',
        };
    }

    public function getCategoryClassesAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_URGENT => 'csa-chip-red',
            self::CATEGORY_IMPORTANT => 'csa-chip-amber',
            default => 'csa-chip-blue',
        };
    }

    /**
     * Status rentang tampil dalam bahasa manusia.
     */
    public function getPeriodLabelAttribute(): ?string
    {
        if (!$this->starts_at && !$this->ends_at) {
            return null;
        }

        if ($this->starts_at && $this->ends_at) {
            return $this->starts_at->translatedFormat('d M Y') . ' - ' . $this->ends_at->translatedFormat('d M Y');
        }

        if ($this->starts_at) {
            return 'Mulai ' . $this->starts_at->translatedFormat('d M Y');
        }

        return 'Sampai ' . $this->ends_at->translatedFormat('d M Y');
    }
}
