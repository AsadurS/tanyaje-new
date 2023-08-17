<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnTypeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->mediumText('template_code',255)->nullable()->change();
            $table->mediumText('lite_template_code',255)->nullable()->change();
            $table->mediumText('askme_code',255)->nullable()->change();
            $table->mediumText('showme_code',255)->nullable()->change();
            $table->mediumText('keepme_code',255)->nullable()->change();
            $table->mediumText('css_code',255)->nullable()->change();
            $table->mediumText('header_code',255)->nullable()->change();
            $table->mediumText('footer_code',255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            //
        });
    }
}
