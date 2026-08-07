<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->slug === 'super-admin') {
            return true;
        }

        return $this->permissions()
            ->where('slug', $permission)
            ->exists();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
