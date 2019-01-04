<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedefineContractSettingsStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::rename('markets', 'price_rates');

        Schema::rename('hotel_contract_settings', 'hotel_contract_settings_origin');

        Schema::create('hotel_contract_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_id')->unsigned();
            $table->integer('hotel_contract_room_type_id')->unsigned();
            $table->integer('hotel_room_type_id')->unsigned();
            $table->date('date');
            $table->integer('capacity')->default(0);
            $table->integer('allotment')->nullable();
            $table->integer('release')->nullable();
            $table->boolean('stop_sale')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('hotel_contract_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_setting_id')->unsigned();
            $table->integer('price_rate_id')->unsigned();
            $table->integer('market_id')->unsigned();
            $table->float('cost_adult');
            $table->float('price_adult');
            $table->float('cost_children_1')->nullable();
            $table->float('price_children_1')->nullable();
            $table->float('cost_children_2')->nullable();
            $table->float('price_children_2')->nullable();
            $table->float('cost_children_3')->nullable();
            $table->float('price_children_3')->nullable();
            $table->float('cost_children_4')->nullable();
            $table->float('price_children_4')->nullable();
            $table->float('cost_children_5')->nullable();
            $table->float('price_children_5')->nullable();
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
        Schema::dropIfExists('hotel_contract_settings');

        Schema::rename('hotel_contract_settings_origin', 'hotel_contract_settings');

        //Schema::rename('price_rates', 'markets');

        Schema::dropIfExists('hotel_contract_prices');
    }
}
