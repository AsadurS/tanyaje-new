<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmpIdIntoMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->string('merchant_emp_id')->nullable();
            $table->string('merchant_postcode')->nullable();
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
