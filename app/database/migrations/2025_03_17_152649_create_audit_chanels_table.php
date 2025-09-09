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
        Schema::create('audit_chanels', function (Blueprint $table) {
            $table->foreignId('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->foreignId('chanel_id')->references('id')->on('trafic_chanels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_chanels');
    }
};
