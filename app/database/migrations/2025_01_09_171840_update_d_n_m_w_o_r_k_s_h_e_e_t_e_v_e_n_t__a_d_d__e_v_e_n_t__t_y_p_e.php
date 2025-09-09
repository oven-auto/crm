<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDNMWORKSHEETEVENTADDEVENTTYPE extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dnm_worksheet_events', function (Blueprint $table) {
            $table->string('event_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dnm_worksheet_events', function (Blueprint $table) {
            //
        });
    }
}
