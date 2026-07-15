<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class PharmacyUser extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'pharmacy_id',
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}