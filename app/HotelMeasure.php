<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelMeasure extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\Contract', 'hotel_contract_measure')
            ->withTimestamps();
    }
}
