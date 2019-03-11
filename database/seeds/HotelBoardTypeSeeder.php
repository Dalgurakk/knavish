<?php

use Illuminate\Database\Seeder;

class HotelBoardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100000; $i++) {
            DB::table('hotel_board_types')->insert([
                'name' => str_random(10),
                'description' => str_random(10),
                'code' => str_random(10),
                'active' => 1,
            ]);
        }
    }
}
