<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTablesAssociatedWithContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('hotel_hotel_board_type', 'hotel_contract_board_type');
        Schema::rename('hotel_hotel_pax_type', 'hotel_contract_pax_type');
        Schema::rename('hotel_hotel_room_type', 'hotel_contract_room_type');
        Schema::rename('hotel_hotel_image', 'hotel_images');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('hotel_contract_board_type', 'hotel_hotel_board_type');
        Schema::rename('hotel_contract_pax_type', 'hotel_hotel_pax_type');
        Schema::rename('hotel_contract_room_type', 'hotel_hotel_room_type');
        Schema::rename('hotel_images', 'hotel_hotel_image');
    }
}
