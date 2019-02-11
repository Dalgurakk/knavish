<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelOfferRange extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function offer() {
        return $this->belongsTo('App\Models\HotelOffer');
    }
}
