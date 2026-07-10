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
        'status'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];

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
        return $this->banner ? Storage::disk('public')->url($this->banner) : null;
    }
}
