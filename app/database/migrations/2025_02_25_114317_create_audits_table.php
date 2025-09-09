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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('appeal_id')->references('id')->on('appeals')->onDelete('cascade');
            $table->integer('complete');
            $table->integer('bonus');
            $table->integer('malus');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
