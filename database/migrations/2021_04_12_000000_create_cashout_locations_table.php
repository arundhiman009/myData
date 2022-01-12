<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashOutLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashout_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
			$table->enum('status', ['0','1'])->default('0');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('address');
            $table->string('lat');
            $table->string('lng');
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
        Schema::dropIfExists('cashout_locations');
    }
}
