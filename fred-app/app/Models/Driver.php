<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Driver extends Authenticable implements JWTSubject
{
    //
    protected $table = 'drivers';
    protected $fillable = ['id', 'name', 'email', 'password', 'register_number'];
    protected $hidden = ['password'];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
