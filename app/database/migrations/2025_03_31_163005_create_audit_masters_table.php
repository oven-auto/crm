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
        Schema::create('audit_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafic_id')->references('id')->on('trafics')->onDelete('cascade');
            $table->foreignId('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->foreignId('author_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->integer('positive_count')->nullable();
            $table->integer('point')->nullable();
            $table->json('result')->nullable();
            $table->enum('status', ['wait', 'close','arbitr'])->default('wait');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_masters');
    }
};
