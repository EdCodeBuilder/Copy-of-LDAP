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
            $table->string('name', 191)->nullable()->comment('Nombre del solicitante');
            $table->string('document', 20)->nullable()->comment('Número de documento del solicitante');
            $table->string('contract', 30)->nullable()->comment('Número de Contrato para el certificado');
            $table->string('virtual_file', 50)->nullable()->comment('Expediente virtual del contrato');
            $table->string('token', 20)->nullable()->comment('Identificador random del documento');
            $table->date('expires_at')->nullable()->comment('Fecha de finalización del contrato');
            $table->string('type', 3)->nullable()->comment('Tipo de certificado');
            $table->string('code', 9)->nullable()->comment('Código de verficación Alamacén');
            $table->unsignedInteger('downloads')->default(0)->nullable()->comment('Cantidad de Descargas');
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
