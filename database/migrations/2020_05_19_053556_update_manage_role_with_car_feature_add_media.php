<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithCarFeatureAddMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "UPDATE `manage_role` SET `add_media` = 1, `edit_media` = 1, `view_media` = 1, `delete_media` = 1 WHERE `user_types_id` = 11;"; 
        DB::connection()->getPdo()->exec($sql);

        $sql = "UPDATE `manage_role` SET `add_media` = 1, `edit_media` = 1, `view_media` = 1, `delete_media` = 1 WHERE `user_types_id` = 12;"; 
        DB::connection()->getPdo()->exec($sql);
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
