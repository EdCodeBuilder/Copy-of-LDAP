<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del registro');
            $table->string('document_type', 12)->nullable()->comment('Tipo de documento de la persona');
            $table->string('document', 12)->nullable()->unique()->comment('Número de documento de la persona');
            $table->string('name', 191)->nullable()->comment('Nombres de la persona');
            $table->string('surname', 191)->nullable()->comment('Apellidos de la persona');
            $table->date('birthdate')->nullable()->comment('Fecha de nacimiento de la persona');
            $table->string('sex', 191)->nullable()->comment('Sexo de la persona');
            $table->string('email', 191)->nullable()->comment('Correo electrónico personal');
            $table->string('institutional_email', 191)->nullable()->comment('Correo electrónico institucional');
            $table->string('phone', 20)->nullable()->comment('Teléfono de Contácto');
            $table->unsignedBigInteger('eps_id')->nullable()->comment('Nombre de la EPS');
            $table->string('eps', 191)->nullable()->comment('Otro nombre de la EPS');
            $table->unsignedBigInteger('afp_id')->nullable()->comment('Nombre de la EPS');
            $table->string('afp', 191)->nullable()->comment('Otro nombre de la EPS');
            $table->unsignedBigInteger('residence_country_id')->nullable()->comment('País de residencia');
            $table->unsignedBigInteger('residence_state_id')->nullable()->comment('Departamento de residencia');
            $table->unsignedBigInteger('residence_city_id')->nullable()->comment('Ciudad de residencia');
            $table->unsignedBigInteger('locality_id')->nullable()->comment('Localidad de residencia');
            $table->unsignedBigInteger('upz_id')->nullable()->comment('UPZ de residencia');
            $table->unsignedBigInteger('neighborhood_id')->nullable()->comment('Barrio de residencia');
            $table->string('neighborhood', 191)->nullable()->comment('Otro nombre del barrio de residencia');
            $table->string('address', 191)->nullable()->comment('Dirección de residencia');
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
        Schema::dropIfExists('contractors');
    }
}
