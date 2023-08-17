<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTypesAddNormalAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        #region Normal Admin
        //delete if record exists for normal admin
        //create normal admin role
        //normal admin user_type_id = 12
        \DB::table('user_types')
            ->where('user_types_id', 12)
            ->delete();


        \DB::table('user_types')->insert(array (
            0 =>
                array (
                    'user_types_id' => 12,
                    'user_types_name' => 'Normal Admin',
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                    'isActive' => 1,
                )
        ));

        //delete exist normal admin role permission
        //insert normal admin role permission
        \DB::table('manage_role')
            ->where('user_types_id', 12)
            ->delete();


        \DB::table('manage_role')->insert(array (
            0 =>
                array (
                    'user_types_id' => 12,
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
                    'manage_admins_view' => 1,
                    'manage_admins_create' => 1,
                    'manage_admins_update' => 1,
                    'manage_admins_delete' => 1,
                    'language_view' => 0,
                    'language_create' => 0,
                    'language_update' => 0,
                    'language_delete' => 0,
                    'profile_view' => 1,
                    'profile_update' => 1,
                    'admintype_view' => 1,
                    'admintype_create' => 1,
                    'admintype_update' => 1,
                    'admintype_delete' => 1,
                    'manage_admins_role' => 1,
                    'add_media' => 0,
                    'edit_media' => 0,
                    'view_media' => 0,
                    'delete_media' => 0,
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
                    'manage_merchants_delete' => 1
                )
        ));

        //create user for normal admin
        //delete existing admin
        \DB::table('users')
            ->where('id', 100)
            ->where('user_name', 'ahbeng')
            ->delete();

        \DB::table('users')->insert(array (
            0 =>
                array (
                    'id' => 100,
                    'role_id' => 12,
                    'user_name' => 'ahbeng',
                    'first_name' => 'Ah Beng',
                    'last_name' => 'Lim',
                    'gender' => null,
                    'default_address_id' => 0,
                    'country_code' => null,
                    'phone' => null,
                    'email' => 'normaladmin01@admin.com',
                    'password' => Hash::make('normaladmin01'),
                    'avatar' => null,
                    'status' => 1,
                    'is_seen' => 0,
                    'phone_verified' => null,
                    'remember_token' => null,
                    'auth_id_tiwilo' => null,
                    'dob' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                )
        ));
        #endregion
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('users')
            ->where('id', 100)
            ->where('user_name', 'ahbeng')
            ->delete();

        \DB::table('manage_role')
            ->where('user_types_id', 12)
            ->delete();

        \DB::table('user_types')
            ->where('user_types_id', 12)
            ->where('user_types_name', 'Normal Admin')
            ->delete();
    }
}
