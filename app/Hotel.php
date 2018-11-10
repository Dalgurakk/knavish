<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Hotel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function images() {
        return $this->hasMany('App\HotelImage');
    }

    public function contracts() {
        return $this->hasMany('App\HotelContract');
    }

    public function hotelChain() {
        return $this->hasOne('App\HotelChain' ,'id', 'hotel_chain_id');
    }

    public function country() {
        return $this->belongsTo('App\Location' ,'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo('App\Location' ,'state_id', 'id');
    }

    public function city() {
        return $this->belongsTo('App\Location' ,'city_id', 'id');
    }
}
