<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCarsTableAdditionsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function(Blueprint $table)
		{
            $table->string('fuel_type', 200)->nullable();
            $table->text('features', 65535)->nullable();
            $table->integer('seats')->nullable();
            $table->string('transmission', 200)->nullable();
            $table->integer('mileage')->nullable();
            $table->string('color', 200)->nullable();
            $table->integer('engine_capacity')->nullable();
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
