<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class packages extends Model
{
    use HasFactory,SoftDeletes;
    protected $hidden = ['type'];
    public function getTitleAttribute(){
        return $this->{'title_' . app()->getLocale()};
    }
    public function plans(){
        return $this->hasMany('\App\Models\plans','package','id');
    }
}
