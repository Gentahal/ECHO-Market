<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiations extends Model
{
    use HasFactory;

    protected $table = 'negotiations';

    protected $fillable = [
        'buyer_id', 'product_id', 'offer_price', 'status'
    ];

    function buyer() {
        return $this->belongsTo(User::class);
    }

    function product() {
        return $this->belongsTo(Product::class);
    }
}
