<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectToPromotionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_email', function (Blueprint $table) {
            $table->mediumText('customer_subject', 255)->nullable();
            $table->mediumText('organiser_subject', 255)->nullable();
            $table->mediumText('admin_subject', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_email', function (Blueprint $table) {
            //
        });
    }
}
