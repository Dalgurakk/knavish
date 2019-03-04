<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableContractRoomTypePaxType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_contract_room_type_pax_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_id')->unsigned();
            $table->integer('hotel_contract_room_type_id')->unsigned();
            $table->integer('hotel_room_type_id')->unsigned();
            $table->integer('hotel_contract_pax_type_id')->unsigned();
            $table->integer('hotel_pax_type_id')->unsigned();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('hotel_contract_room_type_pax_type');
    }
}
