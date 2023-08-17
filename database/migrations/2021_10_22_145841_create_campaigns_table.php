<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('campaign_id');
            $table->string('campaign_name')->nullable();
            $table->string('campaign_image')->nullable();
            $table->string('org_id')->nullable();
            $table->datetime('period_start')->nullable();
            $table->datetime('period_end')->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('status');  
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
        Schema::dropIfExists('campaigns');
    }
}
