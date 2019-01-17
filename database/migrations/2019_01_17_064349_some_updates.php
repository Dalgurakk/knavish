<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SomeUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->boolean('stop_sale')->default(0)->change();
        });

        Schema::table('hotel_contract_client_settings', function (Blueprint $table) {
            $table->foreign('hotel_contract_setting_id')->references('id')->on('hotel_contract_settings')->onDelete('cascade');
            $table->foreign('hotel_contract_client_id')->references('id')->on('hotel_contract_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->boolean('stop_sale')->nullable()->change();
        });

        Schema::table('hotel_contract_client_settings', function (Blueprint $table) {
            $table->dropForeign(['hotel_contract_setting_id']);
            $table->dropForeign(['hotel_contract_client_id']);
        });
    }
}
