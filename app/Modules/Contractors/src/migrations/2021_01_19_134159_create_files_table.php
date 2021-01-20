<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del registro');
            $table->string('name', 191)->nullable()->comment('Nombre del archivo');
            $table->unsignedBigInteger('file_type_id')->comment('Identifica el tipo de archivo');
            $table->unsignedBigInteger('contract_id')->comment('Identifica al tipo de contrato al que pertenece');
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
        Schema::dropIfExists('files');
    }
}
