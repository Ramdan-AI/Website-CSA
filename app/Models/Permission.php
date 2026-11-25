<?php

namespace App\Models;

use App\Support\FlushesQueryCache;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use FlushesQueryCache;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
