<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelOffer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'hotel_offers';

    public function offerType() {
        return $this->hasOne('App\Models\HotelOfferType', 'id', 'hotel_offer_type_id');
    }

    public function contract() {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function ranges() {
        return $this->hasMany('App\Models\HotelOfferRange');
    }

    public function rooms() {
        return $this->hasMany('App\Models\HotelOfferContractRoomType');
    }

    public function boards() {
        return $this->hasMany('App\Models\HotelOfferContractBoardType');
    }
}
