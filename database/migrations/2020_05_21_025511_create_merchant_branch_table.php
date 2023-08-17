<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_branch', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('merchant_name', 200)->nullable();
            $table->string('merchant_phone_no', 200)->nullable();
            $table->integer('state_id');
            $table->integer('city_id');
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('merchant_branch');
    }
}
