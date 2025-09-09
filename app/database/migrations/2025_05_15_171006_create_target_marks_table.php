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
        Schema::create('target_marks', function (Blueprint $table) {
            $table->foreignId('target_id')->references('id')->on('targets')->onDelete('cascade');
            $table->foreignId('mark_id')->references('id')->on('marks')->onDelete('cascade');
            $table->integer('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_marks');
    }
};
