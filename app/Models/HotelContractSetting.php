<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function roomType()
    {
        return $this->hasOne('App\Models\HotelRoomType', 'id', 'hotel_room_type_id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\HotelContractPrice');
    }

    public function clientSetttings()
    {
        return $this->hasMany('App\Models\HotelContractClientSetting');
    }
}
