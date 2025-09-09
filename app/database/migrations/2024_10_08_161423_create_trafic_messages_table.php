<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraficMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trafic_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('trafic_id')->references('id')->on('trafics')->onDelete('cascade');
            $table->text('message');
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
        Schema::dropIfExists('trafic_messages');
    }
}
