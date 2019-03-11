<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContractPaxType extends Model
{
    protected $table = 'hotel_contract_pax_type';

    public function contract() {
        return $this->belongsTo('App\Models\HotelContract');
    }

    public function paxType() {
        return $this->belongsTo('App\Models\HotelPaxType');
    }

    public function paxTypeRelated() {
        return $this->hasOne('App\Models\HotelPaxType', 'id', 'hotel_pax_type_id');
    }

    public function roomPaxTypeRelations() {
        return $this->hasMany('App\Models\HotelContractRoomTypePaxType');
    }
}
