<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOfferStructureAddRestrictions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_offers', function (Blueprint $table) {
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
            $table->foreign('hotel_offer_type_id')->references('id')->on('hotel_offer_types');
        });

        Schema::table('hotel_offer_ranges', function (Blueprint $table) {
            $table->foreign('hotel_offer_id')->references('id')->on('hotel_offers')->onDelete('cascade');
        });

        Schema::table('hotel_offer_contract_room_type', function (Blueprint $table) {
            $table->foreign('hotel_offer_id')->references('id')->on('hotel_offers')->onDelete('cascade');
            $table->foreign('hotel_room_type_id')->references('id')->on('hotel_room_types')->onDelete('cascade');
            $table->foreign('hotel_contract_room_type_id', 'hotel_contract_room_type_offer_foreign')->references('id')->on('hotel_contract_room_type')->onDelete('cascade');
        });

        /*Schema::table('hotel_offer_contract_setting', function (Blueprint $table) {
            $table->foreign('hotel_offer_id')->references('id')->on('hotel_offers')->onDelete('cascade');
            $table->foreign('hotel_contract_setting_id', 'hotel_contract_setting_offer_foreign')->references('id')->on('hotel_contract_settings')->onDelete('cascade');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_offers', function (Blueprint $table) {
            $table->dropForeign(['hotel_contract_id']);
            $table->dropForeign(['hotel_offer_type_id']);
        });

        Schema::table('hotel_offer_ranges', function (Blueprint $table) {
            $table->dropForeign(['hotel_offer_id']);
        });

        Schema::table('hotel_offer_contract_room_type', function (Blueprint $table) {
            $table->dropForeign(['hotel_offer_id']);
            $table->dropForeign(['hotel_room_type_id']);
            $table->dropForeign('hotel_contract_room_type_offer_foreign');
        });

        /*Schema::table('hotel_offer_contract_setting', function (Blueprint $table) {
            $table->dropForeign(['hotel_offer_id']);
            $table->dropForeign('hotel_contract_setting_offer_foreign');
        });*/
    }
}
