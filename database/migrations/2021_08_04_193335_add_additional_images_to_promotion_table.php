<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalImagesToPromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion', function (Blueprint $table) {
            $table->string('additional_images1')->nullable();
            $table->string('additional_images2')->nullable();
            $table->string('additional_images3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion', function (Blueprint $table) {
            //
        });
    }
}
