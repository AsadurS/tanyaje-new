<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionRedemptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_redemption', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('promotion_id');
            $table->integer('customer_id');
            $table->dateTime('redeem_date');
            $table->string('serial_prefix')->nullable();
            $table->integer('sales_agent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_redemption');
    }
}
