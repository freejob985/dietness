<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    protected $with = ['governorate','region'];
    protected $fillable = ['user','country','governorate','region','piece','street','avenue','house','floor','flat','notes','lat','lng'];
    public function driverObj(){
        return $this->hasOne('App\Models\CitiesDrivers','city','region');
    }
    public function governorate(){
        return $this->hasOne('App\Models\Governorates','id','governorate');
    }
    public function governorate_obj(){
        return $this->hasOne('App\Models\Governorates','id','governorate');
    }
    
    public function region(){
        return $this->hasOne('App\Models\cities','id','region');
    }
    public function regionObj(){
        return $this->hasOne('App\Models\cities','id','region');
    }
}
