<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use NodeTrait;
    use \OwenIt\Auditing\Auditable;

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
