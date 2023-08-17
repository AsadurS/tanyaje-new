<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorIntoTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('colour3', 191)->nullable();
            $table->string('colour4', 191)->nullable();
            $table->string('colour5', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            //
        });
    }
}
