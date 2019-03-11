<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHotelContractClientSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_contract_client_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_client_id')->unsigned();
            $table->integer('hotel_contract_setting_id')->unsigned();
            $table->integer('capacity')->nullable();
            $table->integer('allotment')->nullable();
            $table->integer('release')->nullable();
            $table->boolean('stop_sale')->nullable();
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
        Schema::dropIfExists('hotel_contract_client_settings');
    }
}
