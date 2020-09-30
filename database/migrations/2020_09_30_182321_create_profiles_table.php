<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_type_id')->nullable()->comment('Tipo de documento del usuario');
            $table->date('birthdate')->nullable()->comment('Fecha de nacimiento del usuario');
            $table->string('contract_number')->nullable()->comment('Número de contrato del usuario');
            $table->string('virtual_file')->nullable()->comment('Expediente virtual de usuario');
            $table->unsignedBigInteger('city_id')->nullable()->comment('Ciudad o municipio de nacimiento del usuario');
            $table->unsignedBigInteger('gender_id')->nullable()->comment('Tipo de género');
            $table->unsignedBigInteger('ethnicity_id')->nullable()->comment('Tipo de étnia');
            $table->unsignedBigInteger('gender_identity_id')->nullable()->comment('Identidad de género');
            $table->unsignedBigInteger('user_id')->comment('Usuario relacionado al perfil');
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
        Schema::dropIfExists('profiles');
    }
}
