<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('car_id');
            $table->integer('merchant_id');
            $table->integer('make_id')->unsigned();
            $table->integer('model_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('state_id')->unsigned();
            $table->integer('city_id');
            $table->decimal('price',16,2)->nullable();
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
        Schema::dropIfExists('cars_statistics');
    }
}
