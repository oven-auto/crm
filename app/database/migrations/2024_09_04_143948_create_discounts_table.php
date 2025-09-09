<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 800);
            $table->boolean('returnable')->default(1);
            $table->foreignId('author_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('salon_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreignId('modul_id')->references('id')->on('moduls')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_types');
    }
}
