<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnmWorksheetEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnm_worksheet_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');
            $table->integer('dnm_appeal_id');
            $table->integer('dnm_worksheet_id');
            $table->string('dnm_event_id');
            $table->string('status');
            $table->string('code');

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
        Schema::dropIfExists('dnm_worksheet_events');
    }
}
