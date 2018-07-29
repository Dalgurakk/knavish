<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelChain extends Model
{
    protected $table = 'hotel_hotels_chain';

    public function hotel() {
        return $this->belongsTo('App\Hotel');
    }
}
