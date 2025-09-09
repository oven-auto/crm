<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientAddTypeAddSexAddZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('client_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('trafic_sex_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('trafic_zone_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['client_type_id','trafic_sex_id','trafic_zone_id']);
            $table->dropColumn('client_type_id');
            $table->dropColumn('trafic_sex_id');
            $table->dropColumn('trafic_zone_id');
        });
    }
}
