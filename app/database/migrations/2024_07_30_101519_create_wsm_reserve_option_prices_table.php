<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveOptionPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_option_prices', function (Blueprint $table) {
            $table->foreignId('contract_id')->references('id')->on('wsm_reserve_new_car_contracts')->onDelete('cascade');
            $table->foreignId('option_price_id')->references('id')->on('option_prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wsm_reserve_option_prices');
    }
}
