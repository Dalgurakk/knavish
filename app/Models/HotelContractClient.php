<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractClient extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function hotelContract() {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function client() {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }

    public function priceRate() {
        return $this->hasOne('App\Models\HotelContractMarket', 'id', 'hotel_contract_market_id');
    }
}
