<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTemplateDefaultRedirectUrlIntoTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('a360_redirect_url')->default('{base_url}/show/make')->nullable(false)->change();
            $table->string('askme_redirect_url')->default('{base_url}/ask')->nullable(false)->change();
            $table->string('promotion_redirect_url')->default('{base_url}/keep')->nullable(false)->change();
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
