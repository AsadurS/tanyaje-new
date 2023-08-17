<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltHeaderFieldsMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->Integer('banner_id')->nullable();
            $table->string('banner_color')->default("#000000");
            $table->Integer('logo_id')->nullable();
            $table->string('title_color')->default("#000000");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('banner_image');
            $table->dropColumn('banner_color');
            $table->dropColumn('logo');
            $table->dropColumn('title_color');
        });
    }
}
