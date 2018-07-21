<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $table = 'car_brands';

    public function category() {
        return $this->belongsTo('App\CarCategory');
    }
}
