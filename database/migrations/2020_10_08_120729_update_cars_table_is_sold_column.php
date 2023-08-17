<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCarsTableIsSoldColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function(Blueprint $table) {
            $table->boolean('is_sold')->default(0);
            $table->boolean('is_airtime_hide')->default(0);
            $table->boolean('is_publish')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function(Blueprint $table) {
            $table->dropColumn('is_sold');
            $table->dropColumn('is_airtime_hide');
            $table->dropColumn('is_publish');
        });
    }
}
