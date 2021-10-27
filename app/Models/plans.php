<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class plans extends Model
{
    use HasFactory,SoftDeletes;
    public function getDiscAttribute(){
        return $this->{'description_' . app()->getLocale()};
    }
    public function categories(){
        return $this->hasMany('\App\Models\plan_categories','plan','id')->where('qty','!=',0);
    }
}
