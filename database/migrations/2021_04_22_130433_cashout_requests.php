<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CashoutRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashout_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('to_id')->default(1);
            $table->text('amount');
            $table->enum('method', ['1','2'])->comment("1=online , 2=offline");
            $table->string('paypal_id')->nullable();
            $table->unsignedBigInteger('offline_location_id')->nullable();
            $table->date('offline_location_slot')->nullable();
            $table->enum('status', ['0','1','2'])->default('0')->comment("1=reject, 2=accept");
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('cashout_requests');
    }
}
