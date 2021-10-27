<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class products extends Model
{
    use HasFactory,SoftDeletes;
    public function getNameAttribute($value){
        return $this->{'name_'.app()->getLocale()};
    }
    public function getDescriptionAttribute($value){
        return $this->{'description_'.app()->getLocale()};
    }
    
    public function category_obj(){
        return $this->hasOne('App\Models\main_categories','id','category')->withTrashed();
    }
    public function days_to_edit_product(){
        return $this->hasMany('App\Models\ProductsDays','product','id')->pluck('day');
    }
    public function days(){
        return $this->hasMany('App\Models\ProductsDays','product','id');
    }
}
