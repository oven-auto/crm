<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnmWorksheetAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnm_worksheet_appeals', function (Blueprint $table) {
            $table->foreignId('dnm_worksheet_id')->nullable()->references('id')->on('dnm_worksheets')->onDelete('cascade');
            $table->bigInteger('dnm_appeal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dnm_worksheet_appeals');
    }
}
