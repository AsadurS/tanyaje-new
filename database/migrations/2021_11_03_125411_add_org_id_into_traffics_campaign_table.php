<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgIdIntoTrafficsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffics_campaign', function (Blueprint $table) {
            $table->integer('org_id')->nullable();
            $table->integer('sa_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traffics_campaign', function (Blueprint $table) {
            //
        });
    }
}
