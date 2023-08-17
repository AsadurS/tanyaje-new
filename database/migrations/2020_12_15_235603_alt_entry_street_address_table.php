<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltEntryStreetAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_book', function (Blueprint $table) {
            $table->string('entry_street_address')->nullable()->change();
            $table->string('entry_postcode')->nullable()->change();
            $table->string('entry_city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address_book', function (Blueprint $table) {
            $table->string('entry_street_address')->change();
            $table->string('entry_postcode')->change();
            $table->string('entry_city')->change();
        });
    }
}
