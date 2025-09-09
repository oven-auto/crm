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
        Schema::create('wsm_credit_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_credit_id')->references('id')->on('wsm_credits')->onDelete('cascade');
            $table->integer('period')->default(0);
            $table->integer('cost')->default(0);
            $table->integer('first_pay')->default(0);
            $table->integer('month_pay')->default(0);
            $table->boolean('simple')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_credit_calculations');
    }
};
