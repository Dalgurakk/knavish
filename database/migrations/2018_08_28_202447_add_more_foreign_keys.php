<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_images', function (Blueprint $table) {
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('hotel_chain_id')->unsigned()->nullable()->change();
            $table->integer('country_id')->unsigned()->nullable()->change();
            $table->integer('state_id')->unsigned()->nullable()->change();
            $table->integer('city_id')->unsigned()->nullable()->change();

            $table->foreign('hotel_chain_id')->references('id')->on('hotel_hotels_chain');
            $table->foreign('country_id')->references('id')->on('locations');
            $table->foreign('state_id')->references('id')->on('locations');
            $table->foreign('city_id')->references('id')->on('locations');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('locations');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_images', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['hotel_chain_id']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
