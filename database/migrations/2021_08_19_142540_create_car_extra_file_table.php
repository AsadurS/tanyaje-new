<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarExtraFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_extra_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cars_id')->nullable();
            $table->string('item_attribute_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('size')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('car_extra_file');
    }
}
