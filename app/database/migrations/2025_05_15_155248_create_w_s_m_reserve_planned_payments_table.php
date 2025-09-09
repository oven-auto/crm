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
        Schema::create('wsm_reserve_planned_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->references('id')->on('deal_types')->onDelete('cascade');
            $table->timestamp('date_at');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_reserve_planned_payments');
    }
};
