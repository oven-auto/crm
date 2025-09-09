<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplectationMarkAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complectation_mark_aliases', function (Blueprint $table) {
            $table->foreignId('complectation_id')->references('id')->on('complectations')->onDelete('cascade');
            $table->foreignId('mark_alias_id')->references('id')->on('mark_aliases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complectation_mark_aliases');
    }
}
