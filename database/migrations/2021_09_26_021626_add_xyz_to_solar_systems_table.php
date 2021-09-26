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
            $table->double('x')->nullable();
            $table->double('y')->nullable();
            $table->double('z')->nullable();
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
            $table->removeColumn('x');
            $table->removeColumn('y');
            $table->removeColumn('z');
        });
    }
}
