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
        Schema::create('wsm_service_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_service_id')->references('id')->on('wsm_services')->onDelete('cascade');
            $table->integer('sum')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_service_deductions');
    }
};
