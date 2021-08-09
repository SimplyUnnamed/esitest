<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddXyzToSolarSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->bigInteger('x')->after('region_id');
            $table->bigInteger('y')->after('x');
            $table->bigInteger('z')->after('y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->dropColumn('x');
            $table->dropColumn('y');
            $table->dropColumn('z');
        });
    }
}
