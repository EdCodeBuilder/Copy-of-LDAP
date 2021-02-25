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
            $table->unsignedBigInteger('user_id')->comment('Identifica el usuario que creó el archivo.');
            $table->unsignedBigInteger('file_type_id')->comment('Identifica el tipo de archivo.');
            $table->unsignedBigInteger('contract_id')->comment('Identifica al tipo de contrato al que pertenece.');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')
                ->references('id')
                ->on(env('DB_LDAP_DATABASE').'.users')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('file_type_id')
                ->references('id')
                ->on('file_types')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
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
        Schema::dropIfExists('files');
    }
}
