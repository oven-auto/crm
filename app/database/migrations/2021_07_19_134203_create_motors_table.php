<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('motor_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('motor_transmission_id')->constrained()->onDelete('cascade');
            $table->foreignId('motor_driver_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('power');
            $table->integer('valve');
            $table->float('size');
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
        Schema::dropIfExists('motors');
    }
}
