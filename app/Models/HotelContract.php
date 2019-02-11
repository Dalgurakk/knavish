<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContract extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function paxTypes() {
        return $this
            ->belongsToMany('App\Models\HotelPaxType', 'hotel_contract_pax_type')
            ->withTimestamps();
    }

    public function boardTypes() {
        return $this
            ->belongsToMany('App\Models\HotelBoardType', 'hotel_contract_board_type')
            ->withTimestamps();
    }

    public function roomTypes() {
        return $this
            ->belongsToMany('App\Models\HotelRoomType', 'hotel_contract_room_type')
            ->withTimestamps();
    }

    public function measures() {
        return $this
            ->belongsToMany('App\Models\HotelMeasure', 'hotel_contract_measure')
            ->withTimestamps();
    }

    public function markets() {
        return $this
            ->belongsToMany('App\Models\Market')
            ->withPivot('type', 'value', 'round')
            ->withTimestamps();
    }

    public function priceRates() {
        return $this->hasMany('App\Models\HotelContractMarket');
    }

    public function roomTypeRelations() {
        return $this->hasMany('App\Models\HotelContractRoomType');
    }

    public function hotel() {
        return $this->belongsTo('App\Models\Hotel');
    }

    public function clientContracts() {
        return $this->hasMany('App\Models\HotelContractClient');
    }

    public function settings() {
        return $this->hasMany('App\Models\HotelContractSetting');
    }

    public function offers() {
        return $this->hasMany('App\Models\HotelOffer');
    }
}
