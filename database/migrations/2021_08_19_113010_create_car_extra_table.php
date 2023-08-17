<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_extra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cars_id')->nullable();
            $table->string('item_type_id')->nullable();
            $table->string('item_attribute_id')->nullable();
            $table->string('item_attribute_value')->nullable();
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
        //
    }
}
