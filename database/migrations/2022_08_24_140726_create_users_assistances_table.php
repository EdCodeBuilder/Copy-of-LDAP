<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_assistances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity_id');
            /* $table->foreign('activity_id')->references('id')->on('activity_asisstances'); */
            $table->unsignedInteger('users_id');
            /* $table->foreign('users_id')->references('id')->on('users'); */
            $table->timestamps();
        });
        Schema::table('users_assistances', function (Blueprint $table) {
            $table->foreign('activity_id')->references('id')->on('activity_asisstances');
            $table->foreign('users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_assistances');
    }
}
