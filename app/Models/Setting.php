<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'email',
        'phone',
        'address',
        'instagram',
        'youtube',
        'linkedin',
        'about',
        'registration_open'
    ];

    protected function casts(): array
    {
        return [
            'registration_open' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}