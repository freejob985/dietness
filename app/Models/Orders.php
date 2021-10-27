<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\products;
use App\Models\main_categories;
class Orders extends Model
{
    use HasFactory;
    protected $fillable = ['status'];
    public function getItemsAttribute($value){
        $items = unserialize($value);
        $items = collect($items)->map(function ($item){
            $item['category'] = main_categories::withTrashed()->findOrFail($item['category']);
            $item['products'] = collect($item['products'])->map(function ($product){
                $product = products::withTrashed()->findOrFail($product);
                return $product;
            });
            return $item;
        });
        return $items;
    }
    
     public function package(){
        return $this->hasOne('App\Models\packages','id','package')->withTrashed();
    }
    public function plan(){
        return $this->hasOne('App\Models\plans','id','plan')->withTrashed();
    }
    public function userObj(){
        return $this->hasOne('App\Models\User','id','user')->withTrashed();
    }
    
}
