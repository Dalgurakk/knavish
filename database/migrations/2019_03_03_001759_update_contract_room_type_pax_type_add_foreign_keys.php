<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractRoomTypePaxTypeAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_room_type_pax_type', function (Blueprint $table) {
            $table->foreign('hotel_contract_room_type_id', 'hotel_contract_room_type_foreign')->references('id')->on('hotel_contract_room_type')->onDelete('cascade');
            $table->foreign('hotel_contract_pax_type_id', 'hotel_contract_pax_type_foreign')->references('id')->on('hotel_contract_pax_type')->onDelete('cascade');
            $table->foreign('hotel_contract_id', 'hotel_contract_id_foreign')->references('id')->on('hotel_contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_room_type_pax_type', function (Blueprint $table) {
            $table->dropForeign('hotel_contract_room_type_foreign');
            $table->dropForeign('hotel_contract_pax_type_foreign');
            $table->dropForeign('hotel_contract_id_foreign');
        });
    }
}
