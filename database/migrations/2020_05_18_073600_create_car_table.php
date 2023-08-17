<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->integer('car_id', true);
            $table->string('vim', 200);
            $table->string('stock_number', 200)->nullable();
            $table->integer('make_id')->index('idx_make_id');
            $table->integer('model_id')->index('idx_model_id');
            $table->tinyInteger('status')->comment('1.New, 2.Used');
            $table->longText('image')->nullable();
            $table->string('pdf', 200)->nullable();
            $table->decimal('price', 15);
            $table->integer('state_id')->index('idx_state_id');
            $table->integer('city_id')->index('idx_city_id');
            $table->integer('merchant_id')->index('idx_merchant_id');
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
        Schema::dropIfExists('cars');
    }
}
