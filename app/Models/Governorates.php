<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorates extends Model
{
    use HasFactory;
    public function getNameAttribute(){
        return $this->{'name_' . app()->getLocale()};
    }
    public function cities(){
        return $this->hasMany('App\Models\cities','governorate','id');
    }
}
