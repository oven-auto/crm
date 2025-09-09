<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_states', function (Blueprint $table) {
            $table->id();
            $table->string('logistic_system_name')->unique();
            $table->string('description')->unique();
            $table->string('status')->unique()->index();

            $table->foreign('logistic_system_name')->references('system_name')->on('logistic_states')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_states');
    }
}
