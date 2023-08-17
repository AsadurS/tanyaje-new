<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->nullable();
            $table->string('visitor_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('host')->nullable();
            $table->string('organisation_id')->nullable();
            $table->string('sa_id')->nullable();
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
        Schema::dropIfExists('traffics');
    }
}
