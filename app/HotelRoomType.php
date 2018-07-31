<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelRoomType extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\Contract', 'hotel_contract_room_type')
            ->withTimestamps();
    }
}
