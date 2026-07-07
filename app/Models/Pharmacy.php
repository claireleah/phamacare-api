<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pharmacy extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'owner_name',
        'password',
        'status',
        'plan_id',
        'billing_cycle',
    ];

    protected $hidden = [
        'password',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function plan()
    {
       return $this->belongsTo(\App\Models\Plan::class);
    }
}
