<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'pharmacy_id', 
        'customer_id',
        'customer_name', 
        'customer_phone', 
        'total_amount',
         'status'
         ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}