<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_card_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file')->comment('Imágn de la tarjeta');
            $table->boolean('dark')->comment('Indica si el texto de la imágen es oscura o clara');
            $table->string('template')->comment('Template para exportar PDF');
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
        Schema::dropIfExists('tbl_card_config');
    }
}
