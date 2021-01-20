<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador del módulo en la base de datos');
            $table->string('name')->comment('Nombre del módulo');
            $table->string('area')->nullable()->comment('Área a la que pertenece el módulo');
            $table->text('redirect')->nullable()->comment('Url de redirección al módulo');
            $table->text('image')->nullable()->comment('Ícono o imagen que identifica visualmente el módulo');
            $table->boolean('status')->default(true)->comment('Determina si el módulo está activo o no');
            $table->boolean('missionary')->default(true)->comment('Determina si el módulo es misional o no');
            $table->boolean('compatible')->default(true)->comment('Determina si el módulo es compatible con la nueva versión o no');
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
        Schema::dropIfExists('modules');
    }
}
