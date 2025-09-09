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
        Schema::create('wsm_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
            $table->foreignId('debtor_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('calculation_type')->references('id')->on('credit_tactics')->onDelete('cascade');
            $table->foreignId('creditor_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('status_id')->nullable()->references('id')->on('credit_statuses')->onDelete('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('close')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_credits');
    }
};
