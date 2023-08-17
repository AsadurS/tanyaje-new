<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltMerchantIdSlidersImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sliders_images', function(Blueprint $table) {
            $table->Integer('merchant_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sliders_images', function(Blueprint $table) {
            $table->dropColumn('merchant_id');
        });
    }
}
