<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticable implements JWTSubject
{

    use Notifiable;
    //
    protected $table = 'customers';
    protected $fillable = ['id', 'name', 'email', 'password', 'register_date'];
    protected $hidden = ['password'];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
