<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_actions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 500);
            $table->integer('duration')->default(60);
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_actions');
    }
}
