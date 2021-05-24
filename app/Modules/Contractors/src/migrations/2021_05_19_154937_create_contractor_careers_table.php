<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractor_careers', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del registro');
            $table->unsignedBigInteger('contractor_id')->comment('Identificador del contratista');
            $table->unsignedBigInteger('career_id')->comment('Identificador de la carrera');
            $table->boolean('graduate')->comment('Graduado o no');
            $table->unsignedInteger('year_approved')->comment('Último semestre o curso aprobado');
            $table->timestamps();
            $table->foreign('contractor_id')
                ->references('id')
                ->on('contractors')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('career_id')
                ->references('id')
                ->on('careers')
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
        Schema::dropIfExists('contractor_careers');
    }
}
