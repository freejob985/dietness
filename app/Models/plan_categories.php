<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan_categories extends Model
{
    use HasFactory;
    public function category_obj(){
        return $this->hasOne('\App\Models\main_categories','id','category')->withTrashed();
    }
}
