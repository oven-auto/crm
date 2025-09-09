<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dnm_worksheets', function (Blueprint $table) {
            $table->foreignId('reserve_id')->nullable()->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dnm_worksheets', function (Blueprint $table) {
            $table->dropForegin(['reserve_id']);
            $table->dropColumn('reserve_id');
        });
    }
};
