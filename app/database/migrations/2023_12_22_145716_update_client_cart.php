<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_cars', function (Blueprint $table) {
            $table->foreignId('motor_driver_id')->nullable()->references('id')->on('motor_drivers')->onUpdate('cascade');
            $table->foreignId('motor_transmission_id')->nullable()->references('id')->on('motor_transmissions')->onUpdate('cascade');
            $table->foreignId('motor_type_id')->nullable()->references('id')->on('motor_types')->onUpdate('cascade');
            $table->integer('motor_power')->nullable();
            $table->string('motor_size',10)->nullable();
            $table->foreignId('color_id')->nullable()->references('id')->on('colors')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_cars', function (Blueprint $table) {
            //
        });
    }
}
