<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContractBoardType extends Model
{
    protected $table = 'hotel_contract_board_type';

    public function contract() {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function boardType() {
        return $this->belongsTo('App\Models\HotelBoardType');
    }
}
