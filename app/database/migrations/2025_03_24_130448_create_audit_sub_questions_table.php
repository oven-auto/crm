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
        Schema::create('audit_sub_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->references('id')->on('audit_questions')->onDelete('cascade');
            $table->text('text');
            $table->boolean('multiple');
            $table->foreignId('sort')->nullable()->references('id')->on('audit_sub_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_sub_questions');
    }
};
