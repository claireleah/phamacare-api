<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'amount',
        'start_date',
        'next_billing_date',
        'status',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}