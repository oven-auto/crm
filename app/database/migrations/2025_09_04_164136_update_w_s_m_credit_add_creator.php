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
        Schema::table('wsm_credits', function (Blueprint $table) {
            $table->foreignId('creator_id')->nullable()->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wsm_credits', function (Blueprint $table) {
            $table->dropForeign('wsm_credits_creator_id_foreign');
            $table->dropColumn('creator_id');
        });
    }
};
