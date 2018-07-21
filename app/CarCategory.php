<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    protected $table = 'car_categories';

    public function brands() {
        return $this->hasMany('App\CarBrand');
    }
}
