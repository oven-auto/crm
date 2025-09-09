<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsmReserveIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsm_reserve_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserve_id')->references('id')->on('wsm_reserve_new_cars')->onDelete('cascade');;
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreignId('decorator_id')->references('id')->on('users')->onDelete('cascade');;
            $table->dateTime('date_at');
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
        Schema::dropIfExists('wsm_reserve_issues');
    }
}
