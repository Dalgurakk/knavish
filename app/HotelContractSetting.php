<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelContractSetting extends Model
{
    public function priceRate()
    {
        return $this->belongsTo('App\HotelContractMarket');
    }
}
