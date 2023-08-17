<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->string('generate_qr', 191)->nullable()->default('0')->change();
            $table->string('display_qr', 191)->nullable()->default('0')->change();
            $table->string('contactMe', 191)->nullable()->default('0')->change();
            $table->string('showMe', 191)->nullable()->default('0')->change();
            $table->string('askMe', 191)->nullable()->default('0')->change();
            $table->string('keepMe', 191)->nullable()->default('0')->change();
        });
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
