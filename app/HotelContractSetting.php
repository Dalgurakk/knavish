<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function priceRate()
    {
        return $this->belongsTo('App\HotelContractMarket');
    }
}
