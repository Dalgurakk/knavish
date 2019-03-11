<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelsSetLocationsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('country_id')->default(0)->nullable()->change();
            $table->integer('state_id')->default(0)->nullable()->change();
            $table->integer('city_id')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('country_id')->default(0)->nullable(false)->change();
            $table->integer('state_id')->default(0)->nullable(false)->change();
            $table->integer('city_id')->default(0)->nullable(false)->change();
        });
    }
}
