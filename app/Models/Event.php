<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
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
        'attendance_closed_at',
    ];

    public const TYPE_ACARA = 'acara';

    public const TYPE_STUDY_CLUB = 'study_club';

    public const TYPES = [
        self::TYPE_ACARA => 'Acara',
        self::TYPE_STUDY_CLUB => 'Study Club',
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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/'.$this->banner) : null;
    }
}
