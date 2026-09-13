<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'location',
        'event_date',
        'banner',
        'registration_link',
        'status',
        'attendance_enabled',
        'attendance_code',
        'qr_code',
        'attendance_opened_at',
        'attendance_closed_at'
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'status' => 'string',
            'attendance_enabled' => 'boolean',
            'attendance_opened_at' => 'datetime',
            'attendance_closed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/' . $this->banner) : null;
    }
}
