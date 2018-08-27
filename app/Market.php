<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    public function contracts() {
        return $this->belongsToMany('App\HotelContracts')->withTimestamps();
    }

    public function priceRates() {
        return $this->hasMany('App\HotelContractMarket');
    }
}
