<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    use HasFactory;
    public function getValueAttribute(){
        if($this->type == 'one_value'){
            return $this->value_en;
        }else{
            return $this->{'value_' . app()->getLocale()};
        }
        
    }
}
