<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_role', function($table)
        {
            $table->boolean('manage_merchants_view')->default(0);
            $table->boolean('manage_merchants_create')->default(0);
            $table->boolean('manage_merchants_update')->default(0);
            $table->boolean('manage_merchants_delete')->default(0);
        });

        $sql = "UPDATE `manage_role` SET `manage_merchants_view` = 1, `manage_merchants_create` = 1, `manage_merchants_update` = 1, `manage_merchants_delete` = 1 WHERE `user_types_id` = 1;"; 
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
            $table->dropColumn(['manage_merchants_view', 'manage_merchants_create', 'manage_merchants_update','manage_merchants_delete']);
        });
    }
}
