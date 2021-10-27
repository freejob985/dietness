<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    use HasFactory;
    protected $fillable = ['image','first_word_ar','second_word_ar','description_ar','first_btn_ar','second_btn_ar','first_word_en','second_word_en','description_en','first_btn_en','second_btn_en'];
}
