<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->unsignedInteger('session_id')->nullable();;
            $table->unsignedInteger('url_id')->nullable();
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('sessions');

            $table->foreign('url_id')
                ->references('id')
                ->on('urls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
