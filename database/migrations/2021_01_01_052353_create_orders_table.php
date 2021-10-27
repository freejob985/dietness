<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('package')->nullable();
            $table->foreign('package')->references('id')->on('packages')->onDelete('cascade');
            $table->unsignedBigInteger('plan')->nullable();
            $table->foreign('plan')->references('id')->on('plans')->onDelete('cascade');
            $table->unsignedBigInteger('driver')->nullable();
            $table->foreign('driver')->references('id')->on('drivers')->onDelete('cascade');
            $table->datetime('day');
            $table->longText('items');
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
        Schema::dropIfExists('orders');
    }
}
