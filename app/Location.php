<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Location extends Model
{
    use NodeTrait;

    public function countries() {
        return $this->hasMany('App\Location' ,'country_id', 'id');
    }

    public function states() {
        return $this->hasMany('App\Location' ,'state_id', 'id');
    }

    public function cities() {
        return $this->hasMany('App\Location' ,'city_id', 'id');
    }

    public function parent() {
        return $this->belongsTo('App\Location' ,'parent_id', 'id');
    }
}
