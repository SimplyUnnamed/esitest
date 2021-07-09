<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefreshTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refresh_token', function (Blueprint $table) {
            $table->integer('character_id');
            $table->smallIncrements('version');
            $table->integer('user_id');
            $table->mediumText('refresh_token');
            $table->longText('scopes');
            $table->dateTime('expires_on');
            $table->text('token');
            $table->string('character_owner_hash');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('character_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refresh_token');
    }
}
