<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractPrice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_contract_prices';

    public function setting() {
        return $this->belongsTo('App\Models\HotelContractSetting', 'hotel_contract_setting_id', 'id');
    }

    public function priceRate() {
        return $this->hasOne('App\Models\HotelContractMarket', 'price_rate_id', 'market_id');
    }
}
