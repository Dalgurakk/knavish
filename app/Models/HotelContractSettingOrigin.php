<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContractSettingOrigin extends Model
{
    protected $table = 'hotel_contract_settings_origin';

    public function priceRate()
    {
        return $this->belongsTo('App\Models\HotelContractMarket');
    }
}
