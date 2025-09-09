<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSMRedemptionFinalizersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_redemption_finalizers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_redemption_car_id')->references('id')->on('wsm_redemption_cars')->onUpdate('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onUpdate('cascade');
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
        Schema::dropIfExists('w_s_m_redemption_finalizers');
    }
}
