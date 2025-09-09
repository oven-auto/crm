<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_cars', function (Blueprint $table) {

            $table->id();

            $table->foreignId('agent_id')->references('id')->on('clients')->onUpdate('cascade');

            $table->foreignId('brand_id')->constrained()->onDelete('cascade');

            $table->foreignId('mark_id')->constrained()->onDelete('cascade');

            $table->foreignId('body_work_id')->nullable()->constrained()->onDelete('cascade');

            $table->foreignId('color_id')->nullable()->references('id')->on('colors')->onUpdate('cascade');

            $table->integer('year')->nullable();

            $table->integer('odometer')->nullable();

            $table->string('register_plate', 20)->nullable();

            $table->string('vin', 17);

            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('motor_driver_id')->nullable()->references('id')->on('motor_drivers')->onUpdate('cascade');

            $table->foreignId('motor_transmission_id')->nullable()->references('id')->on('motor_transmissions')->onUpdate('cascade');

            $table->foreignId('motor_type_id')->nullable()->references('id')->on('motor_types')->onUpdate('cascade');

            $table->integer('motor_power')->nullable();

            $table->string('motor_size', 10)->nullable();

            $table->foreignId('wsm_redemption_car_id')->references('id')->on('wsm_redemption_cars')->onUpdate('cascade');

            $table->integer('purchase_price');

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
        Schema::dropIfExists('used_cars');
    }
}
