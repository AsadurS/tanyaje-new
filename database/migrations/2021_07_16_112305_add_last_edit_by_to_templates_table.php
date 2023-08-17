<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastEditByToTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('template_code', 255)->nullable();
            $table->integer('last_edit_by')->nullable();
            $table->string('call_icon', 191)->nullable();
            $table->string('email_icon', 191)->nullable();
            $table->string('whatsapp_icon', 191)->nullable();
            $table->string('direction_icon', 191)->nullable();
            $table->string('A360_icon', 191)->nullable();
            $table->string('askme_icon', 191)->nullable();
            $table->string('promotion_icon', 191)->nullable();
            $table->string('colour1', 191)->nullable();
            $table->string('colour2', 191)->nullable();
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
