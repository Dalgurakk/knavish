<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHotelContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('hotel_id')->unsigned();
            $table->date('valid_from')->nulleable();
            $table->date('valid_to')->nulleable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_contracts');
    }
}
