<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponseTextIntoCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('response_text')->nullable()->default('Tell Me More');
            $table->string('response_text_color')->nullable()->default('#FFFFFF');
            $table->string('response_button_color')->nullable()->default('#F7701E');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
        });
    }
}
