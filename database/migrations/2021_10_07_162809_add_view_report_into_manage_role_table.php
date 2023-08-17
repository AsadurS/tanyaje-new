<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewReportIntoManageRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_role', function (Blueprint $table) {
            $table->boolean('view_report_organisation')->default(0);
            $table->boolean('view_report_sa')->default(0);
            $table->boolean('view_report_item')->default(0);
            $table->boolean('view_report_promotion')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_role', function (Blueprint $table) {
            //
        });
    }
}
