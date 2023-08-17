<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateManageRoleWithSliderAndCustomerFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "UPDATE settings SET value = 1 WHERE name = 'is_web_purchased';";
        DB::connection()->getPdo()->exec($sql);

        //allow slide show create and edit
        $sql = "UPDATE `manage_role` SET `website_setting_view` = 1, `website_setting_update` = 1 WHERE `user_types_id` = 12;";
        DB::connection()->getPdo()->exec($sql);

        //allow to crud the customer feature
        $sql = "UPDATE `manage_role` SET `customers_view` = 1, `customers_create` = 1, `customers_update` = 1, `customers_delete` = 1 WHERE `user_types_id` = 12;";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "UPDATE settings SET value = 1 WHERE name = 'is_web_purchased';";
        DB::connection()->getPdo()->exec($sql);

        //allow slide show create and edit
        $sql = "UPDATE `manage_role` SET `website_setting_view` = 0, `website_setting_update` = 0 WHERE `user_types_id` = 12;";
        DB::connection()->getPdo()->exec($sql);

        //allow to crud the customer feature
        $sql = "UPDATE `manage_role` SET `customers_view` = 0, `customers_create` = 0, `customers_update` = 0, `customers_delete` = 0 WHERE `user_types_id` = 12;";
        DB::connection()->getPdo()->exec($sql);
    }
}
