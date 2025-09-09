<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modul_appeals', function (Blueprint $table) {
            $table->foreignId('modul_id')->references('id')->on('moduls')->onDelete('cascade');
            $table->foreignId('appeal_id')->references('id')->on('appeals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_appeals');
    }
}
