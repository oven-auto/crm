<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_cars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('mark_id')->constrained()->onDelete('cascade');
            $table->foreignId('body_work_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('year')->nullable();
            $table->integer('odometer')->nullable();
            $table->string('register_plate', 20)->nullable();
            $table->string('vin', 17)->nullable();
            $table->boolean('actual')->default(1);
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('editor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_cars');
    }
}
