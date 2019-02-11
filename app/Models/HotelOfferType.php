<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelOfferType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'hotel_offer_types';

    /*public function offers() {
        return $this->hasMany('App\Models\HotelOffer');
    }*/
}
