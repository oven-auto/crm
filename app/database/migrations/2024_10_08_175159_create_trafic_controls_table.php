<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraficControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trafic_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafic_id')->references('id')->on('trafics')->onDelete('cascade');
            $table->dateTime('begin_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('interval')->nullable();
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
        Schema::dropIfExists('trafic_controls');
    }
}
