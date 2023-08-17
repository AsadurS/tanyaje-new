<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarComparesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_compares', function (Blueprint $table) {
            $table->Integer('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->Integer('car_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_compares');
    }
}
