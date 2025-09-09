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
        Schema::create('service_applicabilities', function (Blueprint $table) {
            $table->foreignId('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->enum('applicability', ['used', 'client', 'new']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_applicabilities');
    }
};
