<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersMerchantsNewrow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            'role_id' => '11',
            'user_name' => 'merchant_merchant1589526807',
            'first_name' => 'merchant',
            'last_name' => 'merchant',
            'phone' => '45678923423',
            'email' => 'merchant01@merchant.com',
            'password' => '$2y$10$FalTha3CpjUDk8d..B5t0u7iti67SbWr3ZL.4QGJfkfmtU0/ISmOq',
            'status' => '1',
            'created_at' => '2020-05-15 07:13:27'
        ]);
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
