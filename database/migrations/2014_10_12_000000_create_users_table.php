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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('guid', 191)->nullable();
            $table->string('name', 191);
            $table->string('surname', 191);
            $table->string('document', 12)->unique()->nullable();
            $table->string('email', 191)->unique()->nullable();
            $table->string('username', 191)->unique();
            $table->text('description')->nullable();
            $table->text('dependency')->nullable();
            $table->text('company')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('ext', 20)->nullable();
            $table->string('password');
            $table->timestamp('expires_at');
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
