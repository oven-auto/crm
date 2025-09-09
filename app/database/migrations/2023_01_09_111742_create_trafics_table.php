<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraficsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trafics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('fathername')->default('');
            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('comment')->default('');

            $table->foreignId('trafic_sex_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('trafic_zone_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('trafic_chanel_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_structure_id')->constrained()->onDelete('cascade');
            $table->foreignId('trafic_appeal_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');

            $table->dateTime('begin_at');
            $table->dateTime('end_at');
            $table->foreignId('manager_id')->references('id')->on('users')->onDelete('cascade');






        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trafics');
    }
}
