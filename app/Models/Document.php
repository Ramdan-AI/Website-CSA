<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'title',
        'file',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloads()
    {
        return $this->hasMany(DocumentDownload::class);
    }
}
