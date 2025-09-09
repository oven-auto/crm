<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название группы');
            $table->integer('sort')->default('1')->comment('Порядок сортировки группы');
            $table->boolean('status')->default('1')->comment('Статус актуальности группы');
            $table->string('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_groups');
    }
}
