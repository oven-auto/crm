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
        Schema::create('wsm_credit_services', function (Blueprint $table) {
            $table->foreignId('wsm_credit_id')->references('id')->on('wsm_credits')->onDelete('cascade');
            $table->foreignId('wsm_service_id')->references('id')->on('wsm_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_credit_services');
    }
};
