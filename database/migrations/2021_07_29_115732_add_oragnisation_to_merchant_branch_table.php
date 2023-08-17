<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOragnisationToMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->string('profile_img')->nullable();
            $table->string('sa_position')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('whatsapp_default_message')->nullable();
            $table->string('waze_url')->nullable();
            $table->integer('verified')->nullable();
            $table->dateTime('verified_since')->nullable();
            $table->dateTime('verified_until')->nullable();
            $table->string('address')->nullable();
            $table->string('generate_qr')->nullable();
            $table->string('display_qr')->nullable();
            $table->string('contactMe')->nullable();
            $table->string('showMe')->nullable();
            $table->string('askMe')->nullable();
            $table->string('keepMe')->nullable();
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
