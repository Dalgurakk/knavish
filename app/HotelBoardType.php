<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelBoardType extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\Contract', 'hotel_contract_board_type')
            ->withTimestamps();
    }
}
