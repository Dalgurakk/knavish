<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRoomTypeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_room_types', function (Blueprint $table) {
            $table->renameColumn('maxpax', 'max_pax');
            $table->renameColumn('minpax', 'max_adult');
            $table->renameColumn('minadult', 'min_adult');
            $table->renameColumn('minchildren', 'min_children');
            $table->renameColumn('maxinfant', 'max_infant');
            $table->integer('max_children')->default(0);
            $table->integer('min_infant')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_room_types', function (Blueprint $table) {
            $table->renameColumn('max_pax', 'maxpax');
            $table->renameColumn('max_adult', 'minpax');
            $table->renameColumn('min_adult', 'minadult');
            $table->renameColumn('min_children', 'minchildren');
            $table->renameColumn('max_infant', 'maxinfant');
            $table->dropColumn('max_children');
            $table->dropColumn('min_infant');
        });
    }
}
