<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
        'description',
        'changes',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Menambahkan',
            'updated' => 'Mengubah',
            'deleted' => 'Menghapus',
            default => ucfirst($this->action),
        };
    }

    public function getActionClassesAttribute(): string
    {
        return match ($this->action) {
            'created' => 'csa-chip-green',
            'updated' => 'csa-chip-amber',
            'deleted' => 'csa-chip-red',
            default => '',
        };
    }

    /**
     * Nama modul dari kelas model, contoh App\Models\Member -> Member.
     */
    public function getSubjectModuleAttribute(): string
    {
        return class_basename($this->subject_type);
    }
}
