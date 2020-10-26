<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Los campos de esta tabla permite nulos ya que sincroniza datos del directorio activo

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del usuario en la base de datos.');
            $table->string('guid', 191)->nullable()->comment('Identificador del usuario en el Directorio Activo');
            $table->string('name', 191)->nullable()->comment('Nombre del usuario');
            $table->string('surname', 191)->nullable()->comment('Apellido del usuario');
            $table->string('document', 12)->unique()->nullable()->comment('Número de documento del usuario');
            $table->string('email', 191)->unique()->nullable()->comment('Correo electrónico del usuario');
            $table->string('username', 191)->unique()->comment('Nombre de usuario para acceso por Directorio Activo');
            $table->text('description')->nullable()->comment('Identifica si es contratista o de planta');
            $table->text('dependency')->nullable()->comment('Área o dependencia del usuario');
            $table->text('company')->nullable()->comment('Identifica si el usuario está en Sede Principal o en otra Sede');
            $table->string('phone', 20)->nullable()->comment('Número de teléfono dentro de la Sede');
            $table->string('ext', 20)->nullable()->comment('Extensión del número de teléfono de la Sede');
            $table->string('password')->nullable()->comment('Contraseña que se sincroniza del Directorio Activo');
            $table->boolean('password_expired')->default(false)->nullable()->comment('Contraseña que se sincroniza del Directorio Activo ha expirado');
            $table->boolean('is_locked')->default(false)->nullable()->comment('Usuario que se sincroniza del Directorio Activo está inactivo');
            $table->timestamp('vacation_start_date')->nullable()->comment('Fecha inicial de vacaciones/suspención del usuario');
            $table->timestamp('vacation_final_date')->nullable()->comment('Fecha final de vacaciones/suspención del usuario');
            $table->timestamp('expires_at')->nullable()->comment('Fecha de vencimiento del contrato');
            $table->unsignedBigInteger('sim_id')->nullable()->comment('Identifica si el usuario ya habia sido creado en el S.I.M. anteriormente');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
