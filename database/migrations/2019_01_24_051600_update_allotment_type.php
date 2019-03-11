<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAllotmentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_client_settings', function (Blueprint $table) {
            $table->renameColumn('capacity', 'allotment_base')->change();
            $table->integer('allotment_sold')->nullable();
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->renameColumn('capacity', 'allotment_base')->change();
            $table->integer('allotment_sold')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_client_settings', function (Blueprint $table) {
            $table->renameColumn('allotment_base', 'capacity')->change();
            $table->dropColumn('allotment_sold');
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->renameColumn('allotment_base', 'capacity')->change();
            $table->dropColumn('allotment_sold');
        });
    }
}
