<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;

class HotelOfferContractBoardType extends Model //implements Auditable
{
    //use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_offer_contract_board_type';

    public function boardType() {
        return $this->hasOne('App\Models\HotelBoardType', 'id', 'hotel_board_type_id');
    }
}
