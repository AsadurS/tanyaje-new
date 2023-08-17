<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagerRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //delete if record exists for manager
        //create manager role
        //manager user_type_id = 13
        \DB::table('user_types')
            ->where('user_types_id', 13)
            ->delete();

        DB::table('user_types')->insert([
            'user_types_id' => '13',
            'user_types_name' => 'Manager',
            'created_at' => date('Y-m-d H:i:s'),
            'isActive' => '1'
        ]);

        Schema::table('manage_role', function($table)
        {
            $table->boolean('manage_managers_view')->default(0);
            $table->boolean('manage_managers_create')->default(0);
            $table->boolean('manage_managers_update')->default(0);
            $table->boolean('manage_managers_delete')->default(0);
        });

        //allow super admin and admin to see this feature
        $sql = "UPDATE `manage_role` " .
            "SET `manage_managers_view` = 1, " .
            "`manage_managers_create` = 1, " .
            "`manage_managers_update` = 1, " .
            "`manage_managers_delete` = 1 " .
            "WHERE `user_types_id` in (1, 12); ";
        DB::connection()->getPdo()->exec($sql);

        //delete exist normal admin role permission
        //insert normal admin role permission
        \DB::table('manage_role')
            ->where('user_types_id', 13)
            ->delete();

        \DB::table('manage_role')->insert(array (
            0 =>
                array (
                    'user_types_id' => 13,
                    'dashboard_view' => 1,
                    'manufacturer_view' => 0,
                    'manufacturer_create' => 0,
                    'manufacturer_update' => 0,
                    'manufacturer_delete' => 0,
                    'categories_view' => 0,
                    'categories_create' => 0,
                    'categories_update' => 0,
                    'categories_delete' => 0,
                    'products_view' => 0,
                    'products_create' => 0,
                    'products_update' => 0,
                    'products_delete' => 0,
                    'news_view' => 0,
                    'news_create' => 0,
                    'news_update' => 0,
                    'news_delete' => 0,
                    'customers_view' => 0,
                    'customers_create' => 0,
                    'customers_update' => 0,
                    'customers_delete' => 0,
                    'tax_location_view' => 0,
                    'tax_location_create' => 0,
                    'tax_location_update' => 0,
                    'tax_location_delete' => 0,
                    'coupons_view' => 0,
                    'coupons_create' => 0,
                    'coupons_update' => 0,
                    'coupons_delete' => 0,
                    'notifications_view' => 0,
                    'notifications_send' => 0,
                    'orders_view' => 0,
                    'orders_confirm' => 0,
                    'shipping_methods_view' => 0,
                    'shipping_methods_update' => 0,
                    'payment_methods_view' => 0,
                    'payment_methods_update' => 0,
                    'reports_view' => 0,
                    'website_setting_view' => 0,
                    'website_setting_update' => 0,
                    'application_setting_view' => 0,
                    'application_setting_update' => 0,
                    'general_setting_view' => 0,
                    'general_setting_update' => 0,
                    'manage_admins_view' => 0,
                    'manage_admins_create' => 0,
                    'manage_admins_update' => 0,
                    'manage_admins_delete' => 0,
                    'language_view' => 0,
                    'language_create' => 0,
                    'language_update' => 0,
                    'language_delete' => 0,
                    'profile_view' => 1,
                    'profile_update' => 1,
                    'admintype_view' => 0,
                    'admintype_create' => 0,
                    'admintype_update' => 0,
                    'admintype_delete' => 0,
                    'manage_admins_role' => 0,
                    'add_media' => 1,
                    'edit_media' => 1,
                    'view_media' => 1,
                    'delete_media' => 1,
                    'edit_management' => 0,
                    'make_create' => 1,
                    'make_delete' => 1,
                    'make_update' => 1,
                    'make_view' => 1,
                    'model_create' => 1,
                    'model_delete' => 1,
                    'model_update' => 1,
                    'model_view' => 1,
                    'state_create' => 1,
                    'state_delete' => 1,
                    'state_update' => 1,
                    'state_view' => 1,
                    'cities_create' => 1,
                    'cities_delete' => 1,
                    'cities_update' => 1,
                    'cities_view' => 1,
                    'manage_merchants_view' => 1,
                    'manage_merchants_create' => 1,
                    'manage_merchants_update' => 1,
                    'manage_merchants_delete' => 1,
                    'car_create' => 1,
                    'car_delete' => 1,
                    'car_update' => 1,
                    'car_view' => 1,
                    'type_create' => 1,
                    'type_delete' => 1,
                    'type_update' => 1,
                    'type_view' => 1,
                    'manage_managers_view' => 0,
                    'manage_managers_create' => 0,
                    'manage_managers_update' => 0,
                    'manage_managers_delete' => 0,
                )
        ));

        //create user for manager
        //delete existing user
        \DB::table('users')
            ->where('user_name', 'manager01')
            ->where('email', 'manager01@manager.com')
            ->delete();

        DB::table('users')->insert([
            'role_id' => '13',
            'user_name' => 'manager01',
            'first_name' => 'manager01',
            'last_name' => 'Lim',
            'phone' => '035557777',
            'email' => 'manager01@manager.com',
            'password' => Hash::make('manager01'),
            'status' => '1',
            'created_at' => '2020-06-01 07:13:27'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('users')
            ->where('user_name', 'manager01')
            ->where('email', 'manager01@manager.com')
            ->delete();

        \DB::table('manage_role')
            ->where('user_types_id', 13)
            ->delete();

        \DB::table('user_types')
            ->where('user_types_id', 13)
            ->where('user_types_name', 'Manager')
            ->delete();

        Schema::table('manage_role', function (Blueprint $table) {
            $table->dropColumn([
                'manage_managers_view',
                'manage_managers_create',
                'manage_managers_update',
                'manage_managers_delete'
            ]);
        });

    }
}
