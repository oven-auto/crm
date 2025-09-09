<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientEventFileAddEventStatusId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_event_files', function (Blueprint $table) {
            $table->foreignId('client_event_status_id')->nullable()->references('id')->on('client_event_statuses')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_event_files', function (Blueprint $table) {
            $table->dropForeign('client_event_files_client_event_status_id_foreign');
            $table->dropColumn('client_event_status_id');
        });
    }
}
