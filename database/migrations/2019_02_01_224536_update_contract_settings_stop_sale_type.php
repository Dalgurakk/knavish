<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractSettingsStopSaleType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_client_settings', function (Blueprint $table) {
            $table->integer('stop_sale')->nullable()->change();
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->integer('stop_sale')->nullable()->change();
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
            $table->boolean('stop_sale')->nullable()->change();
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->boolean('stop_sale')->nullable()->change();
        });
    }
}
