<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelContractPricesAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            $table->foreign('hotel_board_type_id')->references('id')->on('hotel_board_types')->onDelete('cascade');
            $table->foreign('hotel_contract_board_type_id', 'hotel_contract_board_type_foreign')->references('id')->on('hotel_contract_board_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            $table->dropForeign(['hotel_board_type_id']);
            $table->dropForeign('hotel_contract_board_type_foreign');
        });
    }
}
