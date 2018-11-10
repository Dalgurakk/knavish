<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'hotel_images';

    public function hotel() {
        return $this->belongsTo('App\Models\Models\Hotel');
    }
}
