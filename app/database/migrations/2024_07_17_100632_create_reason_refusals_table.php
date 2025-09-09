<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReasonRefusalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reason_refusals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('for_lead');
            $table->boolean('for_reject');
            $table->boolean('reject_contract');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reason_refusals');
    }
}
