<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('client_event_templates', function (Blueprint $table) {
            $table->foreignId('process_id')->nullable()->references('id')->on('client_event_template_processes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_event_templates', function (Blueprint $table) {
            $table->dropForeign('client_event_templates_process_id_foreign');
            $table->dropColumn('process_id');
        });
    }
};
