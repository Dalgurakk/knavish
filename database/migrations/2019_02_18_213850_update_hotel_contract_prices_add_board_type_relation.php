<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelContractPricesAddBoardTypeRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            $table->integer('hotel_contract_board_type_id')->unsigned();
            $table->integer('hotel_board_type_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_prices', function($table) {
            $table->dropColumn('hotel_contract_board_type_id');
            $table->dropColumn('hotel_board_type_id');
        });
    }
}
