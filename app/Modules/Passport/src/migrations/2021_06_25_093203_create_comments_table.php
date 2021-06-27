<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('comment');
            $table->unsignedInteger('agreement_id');
            $table->timestamps();
            $table->foreign('agreement_id')
                    ->references('id')
                    ->on("tbl_agreements");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_comments');
    }
}
