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
        Schema::create('audit_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->integer('sort')->nullable();        
            $table->string('name');            
            $table->text('text');
            $table->integer('weight')->nullable();
            $table->boolean('is_stoped')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_questions');
    }
};
