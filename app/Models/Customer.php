<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];
    protected $guard = 'customer';
    public function addBY()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
