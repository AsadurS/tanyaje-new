<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSegmentToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('template_id')->nullable();
            $table->string('corporate_email')->nullable();
            $table->string('corporate_phone')->nullable();
            $table->string('brn_no')->nullable();
            $table->string('bank_id1')->nullable();
            $table->string('bank_acc_name1')->nullable();
            $table->string('bank_acc_no1')->nullable();
            $table->string('bank_id2')->nullable();
            $table->string('bank_acc_name2')->nullable();
            $table->string('bank_acc_no2')->nullable();
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
            //
        });
    }
}
