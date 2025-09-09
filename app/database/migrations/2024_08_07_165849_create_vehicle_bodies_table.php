<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_bodies', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->references('id')->on('vehicle_types')->onDelete('cascade');
            $table->foreignId('bodywork_id')->references('id')->on('body_works')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_bodies');
    }
}
