<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_agreements', function (Blueprint $table) {
            $table->longText('description')->after('agreement')->nullable();
            $table->unsignedBigInteger('rate_5')->after('description')->default(0);
            $table->unsignedBigInteger('rate_4')->after('rate_5')->default(0);
            $table->unsignedBigInteger('rate_3')->after('rate_4')->default(0);
            $table->unsignedBigInteger('rate_2')->after('rate_3')->default(0);
            $table->unsignedBigInteger('rate_1')->after('rate_2')->default(0);
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
        Schema::table('tbl_agreements', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'rate_5',
                'rate_4',
                'rate_3',
                'rate_2',
                'rate_1',
            ]);
            $table->dropSoftDeletes();
        });
    }
}
