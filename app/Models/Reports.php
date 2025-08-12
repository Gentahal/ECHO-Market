<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reason',
    ];

    function reporter() {
        return $this->belongsTo(User::class);
    }

    function reported_user_id() {
        return $this->belongsTo(User::class);
    }
}
