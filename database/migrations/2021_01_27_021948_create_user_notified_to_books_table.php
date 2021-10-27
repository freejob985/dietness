<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotifiedToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notified_to_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('subscription')->nullable();
            $table->foreign('subscription')->references('id')->on('subscriptions')->onDelete('cascade');
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
        Schema::dropIfExists('user_notified_to_books');
    }
}
