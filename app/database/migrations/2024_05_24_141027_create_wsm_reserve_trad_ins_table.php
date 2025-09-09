<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveTradInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_trade_ins', function (Blueprint $table) {
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');
            $table->foreignId('used_car_id')->references('id')->on('used_cars')->onDelete('cascade');
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
        Schema::dropIfExists('wsm_reserve_trad_ins');
    }
}
