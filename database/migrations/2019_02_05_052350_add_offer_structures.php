<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_offer_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        DB::table('hotel_offer_types')->insert(
            array(
                array('code' => 'early_booking', 'name' => 'Early Booking', 'active' => true),
                //array('code' => 'night_offer', 'name' => 'Night\'s Offer', 'active' => false)
            )
        );

        DB::table('hotel_measures')
            ->where('code', 'offer')
            ->update(['active' => true]);

        Schema::create('hotel_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_contract_id')->unsigned();
            $table->integer('hotel_offer_type_id')->unsigned();
            $table->integer('priority')->default(0);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('non_refundable')->default(false);
            $table->boolean('apply_with_other_offers')->default(false);
            $table->date('booking_date_from')->nullable();
            $table->date('booking_date_to')->nullable();
            $table->date('payment_date')->nullable();
            $table->float('percentage_due')->nullable();
            $table->float('discount')->nullable();
            $table->integer('discount_type')->nullable();
            $table->integer('minimum_stay')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('hotel_offer_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_offer_id')->unsigned();
            $table->date('from');
            $table->date('to');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('hotel_offer_contract_room_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_offer_id')->unsigned();
            $table->integer('hotel_contract_room_type_id')->unsigned();
            $table->integer('hotel_room_type_id')->unsigned();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        /*Schema::create('hotel_offer_contract_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_offer_id')->unsigned();
            $table->integer('hotel_contract_setting_id')->unsigned();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_offer_types');

        Schema::dropIfExists('hotel_offers');

        Schema::dropIfExists('hotel_offer_ranges');

        Schema::dropIfExists('hotel_offer_contract_room_type');

        //Schema::dropIfExists('hotel_offer_contract_setting');

        DB::table('hotel_measures')
            ->where('code', 'offer')
            ->update(['active' => false]);
    }
}
