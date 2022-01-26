<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePagoPse extends Migration
{
      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::connection('mysql_pse')->create('pago_pse', function (Blueprint $table) {
                  $table->bigIncrements('id');
                  $table->integer('id_parque');
                  $table->integer('id_servicio');
                  $table->bigInteger('identificacion');
                  $table->integer('tipo_identificacion');
                  $table->text('codigo_pago');
                  $table->text('id_transaccion_pse');
                  $table->text('email');
                  $table->text('nombre');
                  $table->text('apellido');
                  $table->text('telefono');
                  $table->text('estado');
                  $table->text('estado_banco');
                  $table->text('concepto');
                  $table->float('total');
                  $table->float('iva');
                  $table->bigInteger('permiso');
                  $table->string('tipo_permiso');
                  $table->integer('id_reserva')->nullable();
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
            Schema::dropIfExists('table_pago_pse');
      }
}
