<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelPaxType extends Model
{
    public function contracts()
    {
        return $this
            ->belongsToMany('App\Contract', 'hotel_contract_pax_type')
            ->withTimestamps();
    }
}
