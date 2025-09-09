<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisckountChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disckount_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disckount_checks');
    }
}
