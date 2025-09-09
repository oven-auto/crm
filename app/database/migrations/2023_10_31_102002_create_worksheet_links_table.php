<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_links', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('icon');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
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
        Schema::dropIfExists('worksheet_links');
    }
}
