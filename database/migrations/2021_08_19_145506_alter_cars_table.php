<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vim', 200)->nullable()->change();
            $table->integer('make_id')->nullable()->change();
            $table->integer('model_id')->nullable()->change();
            $table->integer('state_id')->nullable()->change();
            $table->integer('city_id')->nullable()->change();
            $table->integer('year_make')->nullable()->change();
            $table->integer('type_id')->nullable()->change();
            $table->string('sp_account', 200)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
