<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cities extends Model
{
    use HasFactory;
    public function getNameAttribute(){
        return $this->{'name_' . app()->getLocale()};
    }
    public function governorate_obj(){
        return $this->hasOne('App\Models\Governorates','id','governorate');
    }
    public function city_diver_obj(){
        return $this->hasOne('App\Models\cities_drivers','city','id');
    }
    public function city_diverers_obj(){
        return $this->hasMany('App\Models\cities_drivers','city','id');
    }
}
