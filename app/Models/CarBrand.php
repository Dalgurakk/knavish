<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CarBrand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'car_brands';

    public function category() {
        return $this->belongsTo('App\Models\CarCategory');
    }
}
