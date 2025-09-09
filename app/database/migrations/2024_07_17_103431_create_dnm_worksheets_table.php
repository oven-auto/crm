<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnmWorksheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnm_worksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
            $table->bigInteger('dnm_id');
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
        Schema::dropIfExists('dnm_worksheets');
    }
}
