<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSMRedemptionCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_redemption_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('client_car_id')->references('id')->on('client_cars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('redemption_type_id')->references('id')->on('redemption_types')->onUpdate('cascade');
            $table->foreignId('car_sale_sign_id')->references('id')->on('car_sale_signs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreignId('client_id')->references('id')->on('clients')->onUpdate('cascade');
            $table->foreignId('redemption_status_id')->references('id')->on('redemption_statuses')->onUpdate('cascade');
            $table->integer('expectation')->default(0);
            $table->foreignId('finalizer')->nullable()->references('id')->on('users')->onUpdate('cascade');
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
        Schema::dropIfExists('wsm_redemption_cars');
    }
}
