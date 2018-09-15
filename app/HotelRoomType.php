<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelRoomType extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\HotelContract', 'hotel_contract_room_type')
            ->withTimestamps();
    }
}
