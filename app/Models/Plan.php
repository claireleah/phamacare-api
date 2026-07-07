<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'monthly_price',
        'yearly_price',
        'max_products',
        'max_riders',
        'stock_alerts',
        'sales_reports',
        'priority_support',
    ];

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class);
    }
}