<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerColorImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_color_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_color_id')->references('id')->on('dealer_colors')->onDelete('cascade');
            $table->string('image', 500);
            $table->foreignId('body_work_id')->references('id')->on('body_works')->onDelete('cascade');
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
        Schema::dropIfExists('dealer_color_images');
    }
}
