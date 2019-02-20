<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContractRoomType extends Model
{
    protected $table = 'hotel_contract_room_type';

    public function settings() {
        return $this->hasMany('App\Models\HotelContractSetting');
    }

    public function contract() {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function roomType() {
        return $this->belongsTo('App\Models\HotelRoomType');
    }
}
