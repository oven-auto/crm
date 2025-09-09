<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('text');
            $table->integer('type')->default(0);
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
        Schema::dropIfExists('wsm_reserve_comments');
    }
}
