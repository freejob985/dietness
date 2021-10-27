<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
class restricted_orders extends Model
{
    use HasFactory;
    public function getDayAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
        
    }
    public function userObj(){
        return $this->hasOne('App\Models\User','id','user')->withTrashed();
    }
}
