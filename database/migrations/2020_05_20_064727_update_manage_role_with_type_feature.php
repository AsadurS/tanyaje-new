<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithTypeFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_role', function(Blueprint $table)
		{
            $table->boolean('type_create')->default(0);
            $table->boolean('type_delete')->default(0);
            $table->boolean('type_update')->default(0);
            $table->boolean('type_view')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `type_create` = 1, `type_delete` = 1, `type_update` = 1, `type_view` = 1 WHERE `user_types_id` = 1;"; 
        DB::connection()->getPdo()->exec($sql);

        $sql = "UPDATE `manage_role` SET `type_create` = 1, `type_delete` = 1, `type_update` = 1, `type_view` = 1 WHERE `user_types_id` = 12;"; 
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_role', function (Blueprint $table) {
            $table->dropColumn(['type_create']);
            $table->dropColumn(['type_delete']);
            $table->dropColumn(['type_update']);
            $table->dropColumn(['type_view']);
        });
    }
}
