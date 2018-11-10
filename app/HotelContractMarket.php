<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractMarket extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_contract_market';

    public function settings() {
        return $this->hasMany('App\HotelContractSetting');
    }

    public function contract() {
        return $this->belongsTo('App\HotelContract');
    }

    public function market() {
        return $this->belongsTo('App\Market');
    }
}
