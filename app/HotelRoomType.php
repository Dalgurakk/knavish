<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelRoomType extends Model
{
    public function hotels()
    {
        return $this
            ->belongsToMany('App\Hotel')
            ->withTimestamps();
    }
}
