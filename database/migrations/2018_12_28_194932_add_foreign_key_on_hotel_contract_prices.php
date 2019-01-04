<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyOnHotelContractPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            $table->foreign('hotel_contract_setting_id')->references('id')->on('hotel_contract_settings')->onDelete('cascade');
            $table->foreign('price_rate_id')->references('id')->on('hotel_contract_market')->onDelete('cascade');
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->foreign('hotel_contract_room_type_id')->references('id')->on('hotel_contract_room_type')->onDelete('cascade');
        });

        Schema::table('hotel_contract_market', function (Blueprint $table) {
            $table->index(['market_id', 'hotel_contract_id'], 'index_market_id_hotel_contract_id');
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->index(['hotel_contract_id', 'date', 'hotel_room_type_id'], 'index_hotel_contract_id_date_hotel_room_type_id');
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->index(['hotel_room_type_id', 'hotel_contract_id'], 'index_hotel_room_type_id_hotel_contract_id');
        });

        Schema::dropIfExists('audits');

        Schema::create('audits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_username')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_name')->nullable();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'user_type']);
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
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            $table->dropForeign(['hotel_contract_setting_id']);
            $table->dropForeign(['price_rate_id']);
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->dropForeign(['hotel_contract_room_type_id']);
        });

        Schema::table('hotel_contract_market', function (Blueprint $table) {
            $table->dropIndex('index_market_id_hotel_contract_id');
        });

        Schema::table('hotel_contract_settings', function (Blueprint $table) {
            $table->dropIndex('index_hotel_contract_id_date_hotel_room_type_id');
        });

        Schema::table('hotel_contract_room_type', function (Blueprint $table) {
            $table->dropIndex('index_hotel_room_type_id_hotel_contract_id');
        });

        Schema::drop('audits');
    }
}
