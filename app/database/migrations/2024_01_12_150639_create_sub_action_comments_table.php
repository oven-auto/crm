<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubActionCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_action_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_action_id')->references('id')->on('sub_actions')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('sub_action_comments');
    }
}
