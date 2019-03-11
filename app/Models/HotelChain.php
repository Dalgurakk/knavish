<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelChain extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_hotels_chain';

    public function hotel() {
        return $this->belongsTo('App\Models\Hotel');
    }
}
