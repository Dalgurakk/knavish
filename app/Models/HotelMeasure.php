<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelMeasure extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function contracts()
    {
        return $this
            ->belongsToMany('App\Models\HotelContract', 'hotel_contract_measure')
            ->withTimestamps();
    }
}
