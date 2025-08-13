<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'merchant_id',
        'status',
        'total_amount'
    ];

    // Relasi ke User sebagai Customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi ke User sebagai Merchant
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    // Relasi ke Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
