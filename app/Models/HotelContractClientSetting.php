<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelContractClientSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function contract()
    {
        return $this->belongsTo('App\Models\HotelContractClient');
    }

    public function setting()
    {
        return $this->belongsTo('App\Models\HotelContractSetting');
    }
}
