<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Market extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function contracts() {
        return $this->belongsToMany('App\HotelContracts')->withTimestamps();
    }

    public function priceRates() {
        return $this->hasMany('App\HotelContractMarket');
    }
}
