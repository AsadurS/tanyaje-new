<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantPaymentMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function(Blueprint $table)
		{
            $table->decimal('merchant_payment',16,2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_branch', function(Blueprint $table)
		{
            $table->dropColumn('merchant_payment');
        });
    }
}
