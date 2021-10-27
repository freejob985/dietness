<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->string('country')->nullable();
            $table->string('governorate')->nullable();
            $table->string('region')->nullable();
            $table->string('piece')->nullable();
            $table->string('street')->nullable();
            $table->string('avenue')->nullable();
            $table->string('house')->nullable();
            $table->string('floor')->nullable();
            $table->string('flat')->nullable();
            $table->double('lat',15,8)->nullable();
            $table->double('lng',15,8)->nullable();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
