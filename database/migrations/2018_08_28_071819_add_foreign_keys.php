<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_brands', function (Blueprint $table) {
            $table->foreign('car_category_id')->references('id')->on('car_categories');
        });

        Schema::table('hotel_contract_board_type', function (Blueprint $table) {
            $table->foreign('hotel_board_type_id')->references('id')->on('hotel_board_types');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });

        Schema::table('hotel_contract_market', function (Blueprint $table) {
            $table->foreign('market_id')->references('id')->on('markets');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });

        Schema::table('hotel_contract_measure', function (Blueprint $table) {
            $table->foreign('hotel_measure_id')->references('id')->on('hotel_measures');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });

        Schema::table('hotel_contract_pax_type', function (Blueprint $table) {
            $table->foreign('hotel_pax_type_id')->references('id')->on('hotel_pax_types');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->foreign('hotel_room_type_id')->references('id')->on('hotel_room_types');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->foreign('hotel_contract_market_id')->references('id')->on('hotel_contract_market')->onDelete('cascade');
        });

        Schema::table('hotel_contracts', function (Blueprint $table) {
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_brands', function (Blueprint $table) {
            $table->dropForeign(['car_category_id']);
        });

        Schema::table('hotel_contract_board_type', function (Blueprint $table) {
            $table->dropForeign(['hotel_board_type_id']);
            $table->dropForeign(['hotel_contract_id']);
        });

        Schema::table('hotel_contract_market', function (Blueprint $table) {
            $table->dropForeign(['market_id']);
            $table->dropForeign(['hotel_contract_id']);
        });

        Schema::table('hotel_contract_measure', function (Blueprint $table) {
            $table->dropForeign(['hotel_measure_id']);
            $table->dropForeign(['hotel_contract_id']);
        });

        Schema::table('hotel_contract_pax_type', function (Blueprint $table) {
            $table->dropForeign(['hotel_pax_type_id']);
            $table->dropForeign(['hotel_contract_id']);
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->dropForeign(['hotel_room_type_id']);
            $table->dropForeign(['hotel_contract_id']);
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->dropForeign(['hotel_contract_market_id']);
        });

        Schema::table('hotel_contracts', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
        });
    }
}
