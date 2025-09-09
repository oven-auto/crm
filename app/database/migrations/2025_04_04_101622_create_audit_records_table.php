<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_id')->references('id')->on('audit_masters')->onDelete('cascade');
            $table->binary('file');
            $table->timestamps();
        });
        
        DB::statement("ALTER TABLE audit_records MODIFY  COLUMN  file LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_records');
    }
};
