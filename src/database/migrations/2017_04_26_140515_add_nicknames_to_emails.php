<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNicknamesToEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sessionUrls = \Shrizzer\Models\SessionUser::all();

        foreach ($sessionUrls as $su) {
            if ($su->nickname == null) {
                $su->nickname = \Shrizzer\Helpers\NickNameGenerator::generateNicknameByEmail($su->user()->first()->email);
                $su->save();
            }
        }
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
