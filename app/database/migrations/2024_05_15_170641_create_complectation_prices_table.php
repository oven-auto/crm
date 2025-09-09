<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplectationPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complectation_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complectation_id')->references('id')->on('complectations')->onDelete('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('price');
            $table->dateTime('begin_at');
            $table->softDeletes();
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
        Schema::dropIfExists('complectation_prices');
    }
}
