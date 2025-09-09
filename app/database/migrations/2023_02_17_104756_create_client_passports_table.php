<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_passports', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->date('passport_issue_at')->nullable();
            $table->date('birthday_at')->nullable();
            $table->string('address')->nullable();
            $table->string('driving_license')->nullable();
            $table->date('driver_license_issue_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_passports');
    }
}
