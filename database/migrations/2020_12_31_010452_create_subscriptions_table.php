<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('package')->nullable();
            $table->foreign('package')->references('id')->on('packages')->onDelete('cascade');
            $table->unsignedBigInteger('plan')->nullable();
            $table->foreign('plan')->references('id')->on('plans')->onDelete('cascade');
            $table->datetime('from');
            $table->datetime('to');
            $table->float('amount')->comment('current plan price');
            $table->enum('status',['Approved','Created'])->default('Approved');
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
        Schema::dropIfExists('subscriptions');
    }
}
