<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'name',
        'category',
        'price',
        'quantity_in_stock',
        'low_stock_threshold',
        'status',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}