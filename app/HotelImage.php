<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    protected $table = 'hotel_hotel_image';

    public function category() {
        return $this->belongsTo('App\Hotel');
    }
}
