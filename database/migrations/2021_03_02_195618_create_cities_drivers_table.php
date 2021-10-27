<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city')->nullable();
            $table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('driver')->nullable();
            $table->foreign('driver')->references('id')->on('drivers')->onDelete('cascade');
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
        Schema::dropIfExists('cities_drivers');
    }
}
