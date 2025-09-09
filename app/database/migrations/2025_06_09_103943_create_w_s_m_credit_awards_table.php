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
        Schema::create('wsm_credit_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_credit_id')->references('id')->on('wsm_credits')->onDelete('cascade');
            $table->integer('sum')->default(0);
            $table->boolean('completed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_credit_awards');
    }
};
