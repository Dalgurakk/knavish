<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Market extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function contracts() {
        return $this->belongsToMany('App\Models\HotelContracts')->withTimestamps();
    }

    public function priceRates() {
        return $this->hasMany('App\Models\HotelContractMarket');
    }
}
