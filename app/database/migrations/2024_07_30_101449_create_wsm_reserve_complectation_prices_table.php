<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveComplectationPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_complectation_prices', function (Blueprint $table) {
            $table->foreignId('contract_id')->references('id')->on('wsm_reserve_new_car_contracts')->onDelete('cascade');
            $table->foreignId('complectation_price_id')->references('id')->on('complectation_prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wsm_reserve_complectation_prices');
    }
}
