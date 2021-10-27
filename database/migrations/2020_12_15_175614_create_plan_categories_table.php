<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan')->nullable();
            $table->foreign('plan')->references('id')->on('plans')->onDelete('cascade');
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('main_categories')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('max');
            $table->integer('min');
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
        Schema::dropIfExists('plan_categories');
    }
}
