<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnm_client_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_car_id')->references('id')->on('client_cars')->onDelete('cascade');
            $table->integer('dnm_client_car_id');
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
        Schema::dropIfExists('dnm_client_cars');
    }
};
