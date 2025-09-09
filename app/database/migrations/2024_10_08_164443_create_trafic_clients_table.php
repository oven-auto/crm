<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraficClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trafic_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafic_id')->references('id')->on('trafics')->onDelete('cascade');
            $table->foreignId('client_type_id')->nullable()->references('id')->on('client_types')->onDelete('cascade');
            $table->foreignId('trafic_sex_id')->nullable()->references('id')->on('trafic_sexes')->onDelete('cascade');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('fathername')->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('email')->nullable();
            $table->string('inn')->nullable();
            $table->string('company_name')->nullable();
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
        Schema::dropIfExists('trafic_clients');
    }
}
