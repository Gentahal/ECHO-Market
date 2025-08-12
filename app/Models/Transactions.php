<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'status',
        'total_amount',
        'user_id',
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
