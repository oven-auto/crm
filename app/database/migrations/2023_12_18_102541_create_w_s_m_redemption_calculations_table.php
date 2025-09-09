<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSMRedemptionCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_redemption_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_redemption_car_id')->references('id')->on('wsm_redemption_cars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('price_begin')->default(0);
            $table->integer('price_end')->nullable();
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
        Schema::dropIfExists('wsm_redemption_calculations');
    }
}
