<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    public function images() {
        return $this->hasMany('App\HotelImage');
    }
}
