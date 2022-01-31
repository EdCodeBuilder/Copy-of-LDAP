<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoPse extends Migration
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
                  $table->unsignedBigInteger('estado_id');
                  $table->text('estado_banco');
                  $table->text('concepto');
                  $table->text('moneda');
                  $table->decimal('total', 20, 2);
                  $table->decimal('iva', 2, 1);
                  $table->bigInteger('permiso');
                  $table->string('tipo_permiso');
                  $table->integer('id_reserva')->nullable();
                  $table->text('fecha_pago')->nullable();
                  $table->text('user_id_pse');
                  $table->timestamps();
                  $table->softDeletes();

                  $table->foreign('estado_id')->references('id')->on('estado_pse')->onDelete('cascade')->onUpdate('cascade');
            });

      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
            Schema::dropIfExists('pago_pse');
      }
}
