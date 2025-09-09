<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSMRedemptionLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_redemption_links', function (Blueprint $table) {
            $table->id();
            $table->string('url', 1000);
            $table->foreignId('wsm_redemption_car_id')->references('id')->on('wsm_redemption_cars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('icon', 1000);
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
        Schema::dropIfExists('wsm_redemption_links');
    }
}
