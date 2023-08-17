<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithCitiesFeature extends Migration
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
            $table->boolean('cities_create')->default(0);
            $table->boolean('cities_delete')->default(0);
            $table->boolean('cities_update')->default(0);
            $table->boolean('cities_view')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `cities_create` = 1, `cities_delete` = 1, `cities_update` = 1, `cities_view` = 1 WHERE `user_types_id` = 1;"; 
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
            $table->dropColumn(['cities_create']);
            $table->dropColumn(['cities_delete']);
            $table->dropColumn(['cities_update']);
            $table->dropColumn(['cities_view']);
        });
    }
}
