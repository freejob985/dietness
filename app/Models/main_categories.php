<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class main_categories extends Model
{
    use HasFactory,SoftDeletes;
    public function getTitleAttribute(){
        return $this->{'title_' . app()->getLocale()};
    }
    public function products_count(){
        return $this->hasMany('App\Models\products','category','id')->count();
    }
    public function products(){
        return $this->hasMany('App\Models\products','category','id')->orderBy('ordering','asc');
    }
    public function plan_categories(){
        return $this->hasMany('App\Models\plan_categories','category','id');
    }
}
