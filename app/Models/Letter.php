<?php

namespace App\Models;

use App\Support\LogsActivity;
use App\Support\ResolvesStorageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Letter extends Model
{
    use LogsActivity;
    use SoftDeletes;
    use ResolvesStorageUrl;

    public const TYPE_INCOMING = 'incoming';
    public const TYPE_OUTGOING = 'outgoing';

    protected $fillable = [
        'created_by',
        'reference_number',
        'type',
        'subject',
        'correspondent',
        'letter_date',
        'description',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->storageUrl($this->attachment);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_INCOMING ? 'Surat Masuk' : 'Surat Keluar';
    }

    public function scopeIncoming($query)
    {
        return $query->where('type', self::TYPE_INCOMING);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', self::TYPE_OUTGOING);
    }

    /**
     * Nomor surat berurutan untuk tahun berjalan: 001/CSA/IX/2026
     */
    public static function nextReferenceNumber(string $type): string
    {
        $year = now()->year;
        $romanMonths = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $roman = $romanMonths[now()->month - 1];

        $sequence = static::withTrashed()
            ->whereYear('letter_date', $year)
            ->where('type', $type)
            ->count() + 1;

        $prefix = $type === self::TYPE_INCOMING ? 'SM' : 'SK';

        return sprintf('%03d/%s/CSA/%s/%d', $sequence, $prefix, $roman, $year);
    }
}
