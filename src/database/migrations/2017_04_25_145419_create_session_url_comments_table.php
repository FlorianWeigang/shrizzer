<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionUrlCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_url_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_url_id');
            $table->string('comment');
            $table->integer('up_votes')->default(0);
            $table->integer('down_votes')->default(0);
            $table->timestamps();

            $table->foreign('session_url_id')
                ->references('id')
                ->on('session_url');
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
