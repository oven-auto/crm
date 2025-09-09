<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientEventStatusAddBeginTimeAndEndTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_event_statuses', function (Blueprint $table) {
            $table->time('begin_time')->default('09:00');
            $table->time('end_time')->default('21:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_event_statuses', function (Blueprint $table) {
            $table->dropColumn(['begin_time', 'end_time']);
        });
    }
}
