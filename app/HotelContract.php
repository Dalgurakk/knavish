<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelContract extends Model
{
    public function paxTypes()
    {
        return $this
            ->belongsToMany('App\HotelPaxType', 'hotel_contract_pax_type')
            ->withTimestamps();
    }

    public function boardTypes()
    {
        return $this
            ->belongsToMany('App\HotelBoardType', 'hotel_contract_board_type')
            ->withTimestamps();
    }

    public function roomTypes()
    {
        return $this
            ->belongsToMany('App\HotelRoomType', 'hotel_contract_room_type')
            ->withTimestamps();
    }
}
