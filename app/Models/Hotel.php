<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Hotel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function images() {
        return $this->hasMany('App\Models\HotelImage');
    }

    public function contracts() {
        return $this->hasMany('App\Models\HotelContract');
    }

    public function hotelChain() {
        return $this->hasOne('App\Models\HotelChain' ,'id', 'hotel_chain_id');
    }

    public function country() {
        return $this->belongsTo('App\Models\Location' ,'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo('App\Models\Location' ,'state_id', 'id');
    }

    public function city() {
        return $this->belongsTo('App\Models\Location' ,'city_id', 'id');
    }
}
