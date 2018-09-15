<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelBoardType extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\HotelContract', 'hotel_contract_board_type')
            ->withTimestamps();
    }
}
