<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CarCategory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'car_categories';

    public function brands() {
        return $this->hasMany('App\Models\CarBrand');
    }
}
