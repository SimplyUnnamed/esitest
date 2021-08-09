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
            $table->bigIncrements('id');
            $table->integer('system_id');
            $table->integer('npc_kills')->default(0);
            $table->integer('pod_kills')->default(0);
            $table->integer('ship_kills')->default(0);
            $table->timestamps();

            $table->index(['system_id']);
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
