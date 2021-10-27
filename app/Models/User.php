<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function User_otp_count(){
        return $this->hasMany('App\Models\User_otp','user','id');
    }
    public function User_otp(){
        return $this->hasOne('App\Models\User_otp','user','id');
    }
    public function address(){
        return $this->hasOne('App\Models\Addresses','user','id');
    }
    public function current_subscription(){
        return $this->hasOne('App\Models\Subscriptions','user','id');
    }
    public function current_boxes(){
        return $this->hasOne('App\Models\boxes','user','id');
    }
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
    public function packageObj(){
        return $this->hasOne('App\Models\packages','id','package')->withTrashed();
    }
    public function planObj(){
        return $this->hasOne('App\Models\plans','id','plan')->withTrashed();
    }
}
