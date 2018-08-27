<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelContractMarket extends Model
{
    protected $table = 'hotel_contract_market';

    public function settings() {
        return $this->hasMany('App\HotelContractSetting');
    }

    public function contract() {
        return $this->belongsTo('App\Contract');
    }
}
