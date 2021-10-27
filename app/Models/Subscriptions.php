<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;
    protected $with = ['plan_obj'];
    public function user(){
        return $this->hasOne('App\Models\User','id','user')->withTrashed();
    }
     public function userObj(){
        return $this->hasOne('App\Models\User','id','user')->withTrashed();
    }
    public function package(){
        return $this->hasOne('App\Models\packages','id','package')->withTrashed();
    }
    public function package_obj(){
        return $this->hasOne('App\Models\packages','id','package')->withTrashed();
    }
    public function plan_obj(){
        return $this->hasOne('App\Models\plans','id','plan')->withTrashed();
    }
}
