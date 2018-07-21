<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelPaxType extends Model
{
    public function hotels()
    {
        return $this
            ->belongsToMany('App\Hotel')
            ->withTimestamps();
    }
}
