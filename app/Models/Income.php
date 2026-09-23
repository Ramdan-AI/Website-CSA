<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'description',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
