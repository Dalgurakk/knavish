<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelContractSettingsAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->index(['hotel_contract_market_id', 'date']);
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
            $table->dropIndex(['hotel_contract_market_id', 'date']);
        });
    }
}
