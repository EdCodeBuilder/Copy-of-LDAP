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
            $table->unsignedBigInteger('contract_type_id')->nullable()->comment('Identificador del tipo de contratista');
            $table->string('contract', 191)->nullable()->comment('Número del contrato');
            $table->boolean('transport')->default(false)->nullable()->comment('¿Se suministra transporte?');
            $table->string('position', 191)->nullable()->comment('Cargo a desempeñar');
            $table->date('start_date')->nullable()->comment('Fecha inicial del contrato');
            $table->date('final_date')->nullable()->comment('Fecha final del contrato');
            $table->string('total', 191)->nullable()->comment('Valor total del contrato o adición');
            $table->string('day', 191)->nullable()->comment('Día que no trabaja');
            $table->unsignedInteger('risk')->nullable()->comment('Nivel de Riesgo');
            $table->unsignedBigInteger('subdirectorate_id')->nullable()->nullable()->comment('Subdirección a la que pertenece');
            $table->unsignedBigInteger('dependency_id')->nullable()->nullable()->comment('Dependencia a la que pertenece');
            $table->string('other_dependency_subdirectorate', 191)->nullable()->nullable()->comment('Otra dependencia o subdirección a la que pertenece');
            $table->string('supervisor_email', 191)->nullable()->nullable()->comment('Correo electrónico del supervisor');
            $table->unsignedBigInteger('lawyer_id')->nullable()->comment('Identificador del Abogado de Contratación');
            $table->unsignedBigInteger('contractor_id')->nullable()->comment('Identificador del contratista');
            $table->timestamps();
            $table->softDeletes();
            // $table->unique(['contract_type_id', 'contract']);
            $ldap_database = env('DB_LDAP_DATABASE');
            $table->foreign('subdirectorate_id')
                ->references('id')
                ->on("{$ldap_database}.subdirectorates")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('dependency_id')
                ->references('id')
                ->on("{$ldap_database}.areas")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('lawyer_id')
                ->references('id')
                ->on("{$ldap_database}.users")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
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
