<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    protected $table = 'hotel_images';

    public function category() {
        return $this->belongsTo('App\Hotel');
    }
}
