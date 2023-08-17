<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoginEntityIntoMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->integer('role_id')->nullable();
            $table->string('password', 191);
            $table->string('status', 191)->default('1');
            $table->integer('login_counter')->default(0);
            $table->dateTime('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            //
        });
    }
}
