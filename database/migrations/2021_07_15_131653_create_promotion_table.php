<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->bigIncrements('promotion_id');
            $table->string('promotion_name')->nullable();
            $table->integer('segment_id');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('promotion_clicks');
            $table->integer('promotion_redeem');
            $table->integer('status');
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
        Schema::dropIfExists('promotion');
    }
}
