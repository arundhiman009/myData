<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('who made payment');
            $table->integer('transaction_id')->nullable();
            $table->string("game",50);
            $table->integer('to_id')->default(1);
            $table->integer('referrer_id')->nullable();
            $table->double("amount", 8, 2)->nullable();
            $table->string("payment_method",50)->nullable()->comment('referre means refer_by');
            $table->string("promo_name",50)->nullable(); //name of promo code
            $table->double('promo_discount', 8, 2)->nullable();
            $table->enum('promo_type',['0','1'])->nullable()->comment('0 for Fixed 1 for Percentage');
            $table->enum("status",['1','2','3','4'])->default('1')->comment('1->pending 2->approved 3->hold 4->reject');
            $table->enum("admin_status",['0','1','2'])->default('0')->comment('0->Pending 1->Request Sent 2->Approved'); 
            $table->text("comments")->nullable();
            $table->enum("type",['Online','Offline'])->default('Online');
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
        Schema::dropIfExists('payment_infos');
    }
}
