<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del registro');
            $table->boolean('transport')->default(false)->comment('¿Se suministra transporte?');
            $table->string('position', 191)->comment('Cargo a desempeñar');
            $table->date('start_date')->comment('Fecha inicial del contrato');
            $table->date('final_date')->comment('Fecha final del contrato');
            $table->string('total', 191)->comment('Valor total del contrato o adición');
            $table->string('day', 191)->comment('Día que no trabaja');
            $table->unsignedInteger('risk')->comment('Nivel de Riesgo');
            $table->string('subdirectorate', 191)->nullable()->comment('Subdirección a la que pertenece');
            $table->string('dependency', 191)->nullable()->comment('Dependencia a la que pertenece');
            $table->string('other_dependency_subdirectorate', 191)->nullable()->comment('Otra dependencia o subdirección a la que pertenece');
            $table->string('supervisor_email', 191)->nullable()->comment('Correo electrónico del supervisor');
            $table->unsignedBigInteger('contractor_id')->comment('Identificador del contratista');
            $table->unsignedBigInteger('contract_type_id')->comment('Identificador del tipo de contratista');
            $table->timestamps();
            $table->foreign('contractor_id')
                ->references('id')
                ->on('contractors')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('contract_type_id')
                ->references('id')
                ->on('contract_types')
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
        Schema::dropIfExists('contracts');
    }
}
