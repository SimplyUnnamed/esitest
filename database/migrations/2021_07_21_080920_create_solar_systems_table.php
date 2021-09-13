<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_systems', function (Blueprint $table) {
            $table->integer('system_id');
            $table->string('name');
            $table->integer('constellation_id');
            $table->integer('region_id');
            $table->double('security');
            $table->boolean('has_ice')->default(false);

            $table->primary(['system_id']);
            $table->index(['constellation_id']);
            $table->index(['region_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solar_systems');
    }
}
