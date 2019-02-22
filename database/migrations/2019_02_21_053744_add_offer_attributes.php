<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_offers', function (Blueprint $table) {
            $table->integer('booking_type')->nullable();
            $table->integer('days_prior_from')->nullable();
            $table->integer('days_prior_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_offers', function($table) {
            $table->dropColumn('booking_type');
            $table->dropColumn('days_prior_from');
            $table->dropColumn('days_prior_to');
        });
    }
}
