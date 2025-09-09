<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveNewCarContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_new_car_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');;
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');;
            $table->timestamps();

            $table->dateTime('pdkp_offer_at')->nullable(); //Оформление ПДКП
            $table->dateTime('pdkp_delivery_at')->nullable(); //Срок поставки
            $table->foreignId('pdkp_decorator_id')->nullable()->references('id')->on('users')->onDelete('cascade');; //Оформитель пдкп
            $table->dateTime('pdkp_closed_at')->nullable(); //Расторжение пдкп

            $table->dateTime('dkp_offer_at')->nullable(); //оформление дкп
            $table->foreignId('dkp_decorator_id')->nullable()->references('id')->on('users')->onDelete('cascade');; //оформитель дкп
            $table->dateTime('dkp_closed_at')->nullable(); //расторжение дкп
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wsm_reserve_new_car_contracts');
    }
}
