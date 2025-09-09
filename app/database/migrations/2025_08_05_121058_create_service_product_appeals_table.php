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
        Schema::create('service_product_appeals', function (Blueprint $table) {
            $table->foreignId('service_product_id')->references('id')->on('service_products')->onDelete('cascade');
            $table->foreignId('appeal_id')->references('id')->on('appeals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_product_appeals');
    }
};
