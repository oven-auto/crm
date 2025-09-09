<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDetailingCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detailing_costs', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->references('id')->on('users')->onDelete('cascade');;
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
        Schema::table('detailing_costs', function (Blueprint $table) {
            $table->dropForeign('author_id');
            $table->dropSoftDeletes();
            $table->dropTimestamps();
        });
    }
}
