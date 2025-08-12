<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $fillable = [
        'user_id',
        'plan',
        'start_date',
        'end_date',
        'status'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
