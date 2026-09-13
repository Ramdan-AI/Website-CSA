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
        'registration_open',
        'whatsapp_group_link',
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