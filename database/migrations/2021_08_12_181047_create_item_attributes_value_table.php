<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAttributesValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_attributes_value', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_attributes_id')->nullable();
            $table->string('attribute_value')->nullable();
            $table->string('sort_order')->nullable();
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
