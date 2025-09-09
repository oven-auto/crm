<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplectationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Со мной не обсуждали, что нарисовали то нарисовали
        Schema::create('complectations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); //код комплектации
            $table->string('name'); //название
            $table->foreignId('mark_id')->references('id')->on('marks')->onDelete('cascade'); //марка
            $table->foreignId('vehicle_type_id')->references('id')->on('vehicle_types')->onDelete('cascade'); //тип тс легковой/коммерческий
            $table->foreignId('body_work_id')->references('id')->on('body_works')->onDelete('cascade'); //тип кузова
            $table->foreignId('motor_id')->references('id')->on('motors')->onDelete('cascade'); //цепляем как бы id мотора, но так как тут быков лоджик то к каждой комплектации создаем новый мотор сделал так на будущее если моторы будут действительно отдельной сущностью
            $table->foreignId('factory_id')->references('id')->on('factories')->onDelete('cascade'); //страна производства, тут я хз, поидее это должно привязываться к связке марка/модель, но опять же быков лоджик
            $table->foreignId('author_id')->references('id')->on('users')->onDelete('cascade'); //автор создания
            //$table->integer('price');//цена
            $table->timestamps();
            $table->softDeletes(); //типа для архивных
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complectations');
    }
}
