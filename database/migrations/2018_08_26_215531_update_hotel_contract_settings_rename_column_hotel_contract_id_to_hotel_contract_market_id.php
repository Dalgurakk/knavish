<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelContractSettingsRenameColumnHotelContractIdToHotelContractMarketId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hotel_contract_settings');

        Schema::create('hotel_contract_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_market_id')->unsigned();
            $table->date('date');
            $table->json('settings');
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
        Schema::dropIfExists('hotel_contract_settings');
    }
}
