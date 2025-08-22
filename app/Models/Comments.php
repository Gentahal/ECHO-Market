<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = [
        'content',
        'thread_id',
        'user_id'
    ];

    public function thread()
    {
        return $this->belongsTo(Threads::class); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);       
    }
}
