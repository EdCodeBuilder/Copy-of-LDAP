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
            $table->unsignedBigInteger('document_type_id')->nullable()->comment('Tipo de documento de la persona');
            $table->string('document', 12)->nullable()->unique()->comment('Número de documento de la persona');
            $table->string('name', 191)->nullable()->comment('Nombres de la persona');
            $table->string('surname', 191)->nullable()->comment('Apellidos de la persona');
            $table->date('birthdate')->nullable()->comment('Fecha de nacimiento de la persona');
            $table->integer('sex_id')->nullable()->comment('Sexo de la persona');
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
            $table->integer('locality_id')->nullable()->comment('Localidad de residencia');
            $table->integer('upz_id')->nullable()->comment('UPZ de residencia');
            $table->integer('neighborhood_id')->nullable()->comment('Barrio de residencia');
            $table->string('neighborhood', 191)->nullable()->comment('Otro nombre del barrio de residencia');
            $table->string('address', 191)->nullable()->comment('Dirección de residencia');
            $table->timestamp('modifiable')->nullable()->comment('El contratista puede modificar datos si el campo contiene una fecha');
            $table->text('rut')->nullable()->comment('Certificado RUT');
            $table->text('bank')->nullable()->comment('Certificado Cuenta Bancaria');
            $table->boolean('third_party')->default(false)->comment('Tiene terceros creados (BogData, Seven, etc)');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Usuario que crea el registro en el sistema');
            $table->timestamps();
            $sim_database = env('DB_SIM_DATABASE');
            $ldap_database = env('DB_LDAP_DATABASE');
            $park_database = env('DB_PARKS_DATABASE');
            $table->foreign('sex_id')
                ->references('Id_Genero')
                ->on("{$sim_database}.genero")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('user_id')
                ->references('id')
                ->on("{$ldap_database}.users")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('document_type_id')
                ->references('id')
                ->on("{$ldap_database}.document_types")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('eps_id')
                ->references('id')
                ->on("{$ldap_database}.eps")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('afp_id')
                ->references('id')
                ->on("{$ldap_database}.afp")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('residence_country_id')
                ->references('id')
                ->on("{$ldap_database}.countries")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('residence_state_id')
                ->references('id')
                ->on("{$ldap_database}.states")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('residence_city_id')
                ->references('id')
                ->on("{$ldap_database}.cities")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('locality_id')
                ->references('Id_Localidad')
                ->on("{$park_database}.localidad")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('upz_id')
                ->references('Id_Upz')
                ->on("{$park_database}.upz")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('neighborhood_id')
                ->references('IdBarrio')
                ->on("{$park_database}.Barrios")
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
        Schema::dropIfExists('contractors');
    }
}
