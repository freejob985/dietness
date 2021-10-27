<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyUs extends Model
{
    use HasFactory;
    public function getQuestionAttribute(){
        return $this->{'question_'.app()->getLocale()};
    }
    public function getAnswerAttribute(){
        return $this->{'answer_'.app()->getLocale()};
    }
}
