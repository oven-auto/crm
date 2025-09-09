<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraficLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trafic_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafic_id')->references('id')->on('trafics')->onDelete('cascade');
            $table->string('text', 2000);
            $table->string('icon', 500);
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('trafic_links');
    }
}
