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
                  $table->bigInteger('parque_id');
                  $table->bigInteger('servicio_id');
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
                  $table->unsignedBigInteger('medio_id');
                  $table->timestamps();
                  $table->softDeletes();

                  $table->foreign('estado_id')->references('id')->on('estado_pse')->onDelete('cascade')->onUpdate('cascade');
                  $table->foreign('medio_id')->references('id')->on('medio_pago')->onDelete('cascade')->onUpdate('cascade');
                  // $table->foreign('parque_id')->references('id_parque')->on('parque')->onDelete('cascade')->onUpdate('cascade');
                  // $table->foreign('servicio_id')->references('id_servicio')->on('servicio')->onDelete('cascade')->onUpdate('cascade');
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
