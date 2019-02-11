<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;

class HotelOfferContractRoomType extends Model //implements Auditable
{
    //use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_offer_contract_room_type';

    public function roomType() {
        return $this->hasOne('App\Models\HotelRoomType', 'id', 'hotel_room_type_id');
    }
}
