<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Rider extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'password', 
        'status',
        'license_plate', 
        'reset_code',
        'reset_code_expires_at',
        'id_document'
        ];
    protected $hidden = [
        'password'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}