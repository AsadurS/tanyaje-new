<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_response', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('campaign_id')->nullable();
            $table->integer('org_id')->nullable();
            $table->integer('sa_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
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
        Schema::dropIfExists('campaigns_response');
    }
}
