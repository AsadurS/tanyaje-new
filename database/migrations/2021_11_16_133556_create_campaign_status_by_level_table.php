<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStatusByLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_status_by_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organisation_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->integer('status')->default('1')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_status_by_level');
    }
}
