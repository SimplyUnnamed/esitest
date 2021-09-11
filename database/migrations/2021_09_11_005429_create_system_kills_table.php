<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemKillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_kills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('system_id')->index();
            $table->integer('npc_kills')->default(0);
            $table->integer('ship_kills')->default(0);
            $table->integer('pod_kills')->default(0);
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
        Schema::dropIfExists('system_kills');
    }
}
