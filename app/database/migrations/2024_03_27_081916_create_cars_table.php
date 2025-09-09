<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');//author

            $table->foreignId('brand_id')->references('id')->on('brands')->onDelete('cascade');//brand

            $table->foreignId('mark_id')->references('id')->on('marks')->onDelete('cascade');//model

            $table->foreignId('complectation_id')->references('id')->on('complectations')->onDelete('cascade');//complectation

            $table->foreignId('color_id')->references('id')->on('dealer_colors')->onDelete('cascade');//color from dealer palette

            $table->smallInteger('year');

            $table->string('vin', 17);

            $table->boolean('disable_sale')->default(0);

            $table->boolean('disable_off')->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
