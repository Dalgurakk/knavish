<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelContractSetting extends Model
{
    public function contract()
    {
        return $this->belongsTo('App\HotelContract');
    }
}
