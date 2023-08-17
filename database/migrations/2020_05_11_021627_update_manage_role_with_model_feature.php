<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithModelFeature extends Migration
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
            $table->boolean('model_create')->default(0);
            $table->boolean('model_delete')->default(0);
            $table->boolean('model_update')->default(0);
            $table->boolean('model_view')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `model_create` = 1, `model_delete` = 1, `model_update` = 1, `model_view` = 1 WHERE `user_types_id` = 1;"; 
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
            $table->dropColumn(['model_create']);
            $table->dropColumn(['model_delete']);
            $table->dropColumn(['model_update']);
            $table->dropColumn(['model_view']);
        });
    }
}
