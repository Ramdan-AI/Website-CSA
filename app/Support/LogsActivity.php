<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Pasang pencatat aktivitas otomatis pada model.
     *
     * Dipanggil dari booted() model pemakai, sehingga setiap create,
     * update, dan delete tercatat tanpa perlu kode tambahan di controller.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'subject_type' => $model::class,
                'subject_id' => $model->getKey(),
                'subject_label' => $model->activityLabel(),
                'description' => $model->activityDescription('created'),
                'changes' => null,
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->activityChanges();

            // Abaikan update yang tidak mengubah field berarti.
            if (empty($changes)) {
                return;
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'subject_type' => $model::class,
                'subject_id' => $model->getKey(),
                'subject_label' => $model->activityLabel(),
                'description' => $model->activityDescription('updated'),
                'changes' => $changes,
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });

        static::deleted(function ($model) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'subject_type' => $model::class,
                'subject_id' => $model->getKey(),
                'subject_label' => $model->activityLabel(),
                'description' => $model->activityDescription('deleted'),
                'changes' => null,
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Field yang tidak perlu dicatat perubahannya.
     */
    protected function activityHiddenFields(): array
    {
        return ['updated_at', 'created_at', 'remember_token', 'password'];
    }

    /**
     * Ambil daftar perubahan bersih: field, nilai lama, nilai baru.
     */
    protected function activityChanges(): array
    {
        $changes = [];

        foreach ($this->getChanges() as $field => $newValue) {
            if (in_array($field, $this->activityHiddenFields(), true)) {
                continue;
            }

            $changes[$field] = [
                'from' => $this->activityStringify($this->getOriginal($field)),
                'to' => $this->activityStringify($newValue),
            ];
        }

        return $changes;
    }

    /**
     * Ubah nilai apa pun jadi teks pendek agar aman disimpan di JSON.
     */
    protected function activityStringify($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'ya' : 'tidak';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i');
        }

        return \Illuminate\Support\Str::limit((string) $value, 200);
    }

    /**
     * Nama entitas untuk ditampilkan di log.
     * Model dapat menimpanya jika perlu label khusus.
     */
    protected function activityLabel(): ?string
    {
        foreach (['title', 'name', 'subject', 'reference_number'] as $field) {
            if (!empty($this->{$field})) {
                return \Illuminate\Support\Str::limit((string) $this->{$field}, 120);
            }
        }

        return class_basename($this) . ' #' . $this->getKey();
    }

    /**
     * Kalimat siap tampil di halaman log.
     */
    protected function activityDescription(string $action): string
    {
        $module = class_basename($this);
        $label = $this->activityLabel();

        return match ($action) {
            'created' => "Menambahkan {$module}: {$label}",
            'updated' => "Mengubah {$module}: {$label}",
            'deleted' => "Menghapus {$module}: {$label}",
            default => ucfirst($action) . " {$module}: {$label}",
        };
    }
}
