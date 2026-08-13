<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'pharmacy_id', 
        'rider_id',
        'customer_id',
        'customer_name', 
        'customer_phone', 
        'delivery_address',
        'total_amount',
         'status',
         'payment_method',
         ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
}