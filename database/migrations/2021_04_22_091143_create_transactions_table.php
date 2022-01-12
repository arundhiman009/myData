<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
           $table->id();
            $table->integer('user_id')->comment('who made payment');
           /* $table->string("item",255);*/
            $table->string("txn_id",50)->nullable()->unique();
            $table->string("order_id",50)->nullable()->unique();
            $table->float("amount",10,2);
            $table->string("currency",10);
            $table->string("status",50);
           /* $table->enum("admin_status",[1,2,3,4])->default(1)->comment('1->pending 2->approved 3->hold 4->reject');
            $table->text("comments")->nullable();*/
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
        Schema::dropIfExists('transactions');
    }
}
