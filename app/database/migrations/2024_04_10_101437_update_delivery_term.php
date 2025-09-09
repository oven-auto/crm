<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDeliveryTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_terms', function (Blueprint $table) {
            $table->string('text_color');
            //$table->string('body_color');
            $table->text('description');
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
        Schema::table('delivery_terms', function (Blueprint $table) {
            $table->dropColumn('text_color');
            //$table->dropColumn('body_color');
            $table->dropColumn('description');
            $table->dropForeign('author_id');
            $table->dropSoftDeletes();
            $table->dropTimestamps();
        });
    }
}
