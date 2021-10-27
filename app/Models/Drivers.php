<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Drivers extends Authenticatable implements JWTSubject
{
    use HasFactory,SoftDeletes;
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $fillable = ['name','email','mobile','password'];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function cities_in_edit_driver(){
        return $this->hasMany('App\Models\CitiesDrivers','driver','id')->pluck('city');
    }
    public function orders(){
        return $this->hasMany('App\Models\Orders','driver','id');
    }
}
