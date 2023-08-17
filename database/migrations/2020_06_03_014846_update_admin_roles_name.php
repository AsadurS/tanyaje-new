<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAdminRolesName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('user_types')
            ->where('user_types_id', 12)
            ->update(array('user_types_name' => 'Master Admin'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('user_types')
            ->where('user_types_id', 12)
            ->update(array('user_types_name' => 'Normal Admin'));
    }
}
