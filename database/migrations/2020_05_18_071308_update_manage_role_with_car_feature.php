<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithCarFeature extends Migration
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
            $table->boolean('car_create')->default(0);
            $table->boolean('car_delete')->default(0);
            $table->boolean('car_update')->default(0);
            $table->boolean('car_view')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `car_create` = 1, `car_delete` = 1, `car_update` = 1, `car_view` = 1 WHERE `user_types_id` = 1;"; 
        DB::connection()->getPdo()->exec($sql);

        $sql = "UPDATE `manage_role` SET `car_create` = 1, `car_delete` = 1, `car_update` = 1, `car_view` = 1 WHERE `user_types_id` = 11;"; 
        DB::connection()->getPdo()->exec($sql);

        $sql = "UPDATE `manage_role` SET `car_create` = 1, `car_delete` = 1, `car_update` = 1, `car_view` = 1 WHERE `user_types_id` = 12;"; 
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
            $table->dropColumn(['car_create']);
            $table->dropColumn(['car_delete']);
            $table->dropColumn(['car_update']);
            $table->dropColumn(['car_view']);
        });
    }
}
