<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientEventLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_event_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->references('id')->on('client_events')->onDelete('cascade');
            $table->string('url');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('icon')->nullable();
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
        Schema::dropIfExists('client_event_links');
    }
}
