<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->unsignedBigInteger('birthdate_country_id')->after('birthdate')->nullable()->comment('País de nacimiento');
            $table->unsignedBigInteger('birthdate_state_id')->after('birthdate_country_id')->nullable()->comment('Departamento de nacimiento');
            $table->unsignedBigInteger('birthdate_city_id')->after('birthdate_state_id')->nullable()->comment('Ciudad de nacimiento');
            $ldap_database = env('DB_LDAP_DATABASE');
            $table->foreign('birthdate_country_id')
                ->references('id')
                ->on("{$ldap_database}.countries")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('birthdate_state_id')
                ->references('id')
                ->on("{$ldap_database}.states")
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreign('birthdate_city_id')
                ->references('id')
                ->on("{$ldap_database}.cities")
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
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropForeign([
                'contractors_birthdate_city_id_foreign',
                'contractors_birthdate_state_id_foreign',
                'contractors_birthdate_country_id_foreign',
            ]);
            $table->dropColumn([
                'birthdate_city_id',
                'birthdate_state_id',
                'birthdate_country_id',
            ]);
        });
    }
}
