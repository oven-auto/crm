<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnmLadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnm_ladas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafic_id')->nullable()->references('id')->on('trafics')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('worksheet_id')->nullable()->references('id')->on('worksheets')->onDelete('cascade');

            $table->integer('dnm_client_id')->nullable();
            $table->integer('dnm_worksheet_id')->nullable();
            $table->integer('dnm_appeal_id')->nullable();

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
        Schema::dropIfExists('dnm_ladas');
    }
}
