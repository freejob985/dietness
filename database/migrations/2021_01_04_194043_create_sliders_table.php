<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('first_word_en');
            $table->string('second_word_en');
            $table->string('description_en');
            $table->string('first_btn_en');
            $table->string('second_btn_en');
            $table->string('first_word_ar');
            $table->string('second_word_ar');
            $table->string('description_ar');
            $table->string('first_btn_ar');
            $table->string('second_btn_ar');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
