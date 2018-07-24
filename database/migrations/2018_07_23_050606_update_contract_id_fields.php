<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractIdFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_board_type', function (Blueprint $table) {
            $table->renameColumn('hotel_id', 'hotel_contract_id');
        });

        Schema::table('hotel_contract_pax_type', function (Blueprint $table) {
            $table->renameColumn('hotel_id', 'hotel_contract_id');
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->renameColumn('hotel_id', 'hotel_contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_board_type', function (Blueprint $table) {
            $table->renameColumn('hotel_contract_id', 'hotel_id');
        });

        Schema::table('hotel_contract_pax_type', function (Blueprint $table) {
            $table->renameColumn('hotel_contract_id', 'hotel_id');
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->renameColumn('hotel_contract_id', 'hotel_id');
        });
    }
}
