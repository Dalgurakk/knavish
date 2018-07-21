<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    public function paxTypes()
    {
        return $this
            ->belongsToMany('App\HotelPaxType')
            ->withTimestamps();
    }

    public function boardTypes()
    {
        return $this
            ->belongsToMany('App\HotelBoardType')
            ->withTimestamps();
    }

    public function roomTypes()
    {
        return $this
            ->belongsToMany('App\HotelRoomType')
            ->withTimestamps();
    }

    public function images() {
        return $this->hasMany('App\HotelImage');
    }
}
