<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnTypePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion', function (Blueprint $table) {
            $table->mediumText('description',255)->nullable()->change();
            $table->mediumText('fine_print',255)->nullable()->change();
            $table->mediumText('location',255)->nullable()->change();
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
