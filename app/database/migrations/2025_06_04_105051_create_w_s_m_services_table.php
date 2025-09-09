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
        Schema::create('wsm_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
            $table->foreignId('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreignId('provider_id')->nullable()->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('payment_id')->references('id')->on('service_payments')->onDelete('cascade');
            $table->integer('cost')->default(0);
            $table->boolean('simple')->default(false);
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
        Schema::dropIfExists('wsm_services');
    }
};
