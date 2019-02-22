<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoardTypesToOfferStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_offer_contract_board_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_offer_id')->unsigned();
            $table->integer('hotel_contract_board_type_id')->unsigned();
            $table->integer('hotel_board_type_id')->unsigned();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_offer_contract_board_type');
    }
}
