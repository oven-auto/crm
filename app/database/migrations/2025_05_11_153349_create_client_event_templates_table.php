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
        Schema::create('client_event_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->foreignId('group_id')->references('id')->on('event_groups')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('event_types')->onDelete('cascade');
            $table->text('comment');
            $table->integer('begin');
            $table->json('executors');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_event_templates');
    }
};
