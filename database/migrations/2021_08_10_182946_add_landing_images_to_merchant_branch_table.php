<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLandingImagesToMerchantBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branch', function (Blueprint $table) {
            $table->integer('landingpage_version')->nullable()->default('0');
            $table->string('landingpage_images')->nullable();
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
