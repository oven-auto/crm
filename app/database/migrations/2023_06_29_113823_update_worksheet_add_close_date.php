<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorksheetAddCloseDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dateTime('close_at')->nullable();
            $table->foreignId('inspector_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('close_at');
            $table->dropForeign('worksheets_inspector_id_foreign');
            $table->dropColumn('inspector_id');
        });
    }
}
