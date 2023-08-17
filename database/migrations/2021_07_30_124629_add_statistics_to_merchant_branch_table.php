<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatisticsToMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->integer('stat_call')->nullable()->default('0');
            $table->integer('stat_whatsapp')->nullable()->default('0');
            $table->integer('stat_showroom_location')->nullable()->default('0');
            $table->integer('stat_brochure')->nullable()->default('0');
            $table->integer('stat_price_list')->nullable()->default('0');
            $table->integer('stat_promotion')->nullable()->default('0');
            $table->integer('stat_conversion_promotion_redemption')->nullable()->default('0');
            $table->integer('stat_conversion')->nullable()->default('0');
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
