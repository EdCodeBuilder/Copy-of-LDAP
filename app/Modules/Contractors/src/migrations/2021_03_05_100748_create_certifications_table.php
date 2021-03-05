<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191)->comment('Nombre del solicitante');
            $table->string('document', 20)->comment('Número de documento del solicitante');
            $table->string('contract', 30)->comment('Número de Contrato para el certificado');
            $table->string('virtual_file', 50)->comment('Expediente virtual del contrato');
            $table->string('token', 9)->comment('Identificador random del documento');
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
        Schema::dropIfExists('certifications');
    }
}
