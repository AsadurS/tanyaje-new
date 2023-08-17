<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithMakeFeature extends Migration
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
            $table->boolean('make_create')->default(0);
            $table->boolean('make_delete')->default(0);
            $table->boolean('make_update')->default(0);
            $table->boolean('make_view')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `make_create` = 1, `make_delete` = 1, `make_update` = 1, `make_view` = 1 WHERE `user_types_id` = 1;"; 
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
            $table->dropColumn(['make_create']);
            $table->dropColumn(['make_delete']);
            $table->dropColumn(['make_update']);
            $table->dropColumn(['make_view']);
        });
    }
}
