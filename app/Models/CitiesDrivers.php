<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitiesDrivers extends Model
{
    use HasFactory;
    public function city_obj(){
        return $this->hasOne('App\Models\cities','id','city');
    }
}
