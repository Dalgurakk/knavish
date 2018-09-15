<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHotelContractClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_contract_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->integer('hotel_contract_market_id')->unsigned();
            $table->string('name');
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table('hotel_contract_clients', function (Blueprint $table) {
            $table->foreign('hotel_contract_market_id')->references('id')->on('hotel_contract_market');
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('hotel_contract_id')->references('id')->on('hotel_contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_contract_clients');
    }
}
