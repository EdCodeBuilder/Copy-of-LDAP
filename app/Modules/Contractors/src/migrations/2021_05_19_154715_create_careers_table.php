<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del registro');
            $table->string('name', 191)->comment('Nombre de la carrera o tipo de estudio');
            $table->unsignedBigInteger('academic_level_id')->comment('Nivel académico al que pertenece');
            $table->timestamps();
            $table->foreign('academic_level_id')
                ->references('id')
                ->on('academic_level')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('careers');
    }
}
