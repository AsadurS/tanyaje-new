<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersMerchantsNewColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('merchant_name', 200)->nullable();
            $table->string('merchant_phone_no', 200)->nullable();
            $table->integer('state_id');
            $table->integer('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['merchant_name', 'merchant_phone_no', 'state_id','city_id']);
        });
    }
}
