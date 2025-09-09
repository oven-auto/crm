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
        Schema::create('wsm_credit_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wsm_credit_id')->references('id')->on('wsm_credits')->onDelete('cascade');
            $table->integer('carable_id')->index();;
            $table->string('carable_type')->index();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wsm_credit_cars');
    }
};
