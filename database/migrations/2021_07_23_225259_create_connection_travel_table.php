<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionTravelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_travel', function (Blueprint $table) {
            $table->id();
            $table->foreignID('connection_id')->constrained('connections', 'id')
                ->onDelete('cascade');
            $table->integer('character_id')->constrained('characters', 'character_id')
                ->onDelete('cascade');
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
        Schema::dropIfExists('connection_travel');
    }
}
