<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromotionToPromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion', function (Blueprint $table) {
            $table->longText('main_image')->nullable();
            $table->integer('organisation')->nullable();
            $table->integer('max_redeem')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('prefix')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('fine_print', 255)->nullable();
            $table->string('location', 255)->nullable();
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
