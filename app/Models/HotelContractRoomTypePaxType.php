<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContractRoomTypePaxType extends Model
{
    protected $table = 'hotel_contract_room_type_pax_type';

    public function contract() {
        return $this->belongsTo('App\Models\HotelContract');
    }
}
