<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePriceChildrenCostByAdult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_contract_prices', function (Blueprint $table) {
            //$table->boolean('cost_children_1_use_adult')->nullable();
            $table->integer('cost_children_1_use_adult_type')->nullable();
            $table->float('cost_children_1_use_adult_rate')->nullable();

            //$table->boolean('cost_children_2_use_adult')->nullable();
            $table->integer('cost_children_2_use_adult_type')->nullable();
            $table->float('cost_children_2_use_adult_rate')->nullable();

            //$table->boolean('cost_children_3_use_adult')->nullable();
            $table->integer('cost_children_3_use_adult_type')->nullable();
            $table->float('cost_children_3_use_adult_rate')->nullable();

            $table->dropColumn('cost_children_4');
            $table->dropColumn('price_children_4');
            $table->dropColumn('cost_children_5');
            $table->dropColumn('price_children_5');
        });

        Schema::dropIfExists('hotel_contract_settings_origin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_contract_prices', function($table) {
            //$table->dropColumn('cost_children_1_use_adult');
            $table->dropColumn('cost_children_1_use_adult_type');
            $table->dropColumn('cost_children_1_use_adult_rate');

            //$table->dropColumn('cost_children_2_use_adult');
            $table->dropColumn('cost_children_2_use_adult_type');
            $table->dropColumn('cost_children_2_use_adult_rate');

            //$table->dropColumn('cost_children_3_use_adult');
            $table->dropColumn('cost_children_3_use_adult_type');
            $table->dropColumn('cost_children_3_use_adult_rate');

            $table->float('cost_children_4')->nullable();
            $table->float('price_children_4')->nullable();
            $table->float('cost_children_5')->nullable();
            $table->float('price_children_5')->nullable();
        });
    }
}
