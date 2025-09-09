<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSMReserveContractCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_contract_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->references('id')->on('wsm_reserve_new_car_contracts')->onDelete('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('complectation');
            $table->integer('option');
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
        Schema::dropIfExists('wsm_reserve_contract_costs');
    }
}
