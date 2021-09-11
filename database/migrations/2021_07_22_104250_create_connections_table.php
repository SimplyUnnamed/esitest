<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('origin')->constrained('systems', 'id')
                ->onDelete('cascade');
            $table->foreignId('destination')->constrained('systems', 'id')
                ->onDelete('cascade');
            //$table->foreignId('map_id')->constrained('maps', 'id')
            //    ->onDelete('cascade');
            $table->integer('created_by');
            $table->integer('updated_by');

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
        Schema::dropIfExists('connections');
    }
}
