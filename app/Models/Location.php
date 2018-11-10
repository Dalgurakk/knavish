<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use NodeTrait;
    use \OwenIt\Auditing\Auditable;

    public function countries() {
        return $this->hasMany('App\Models\Location' ,'country_id', 'id');
    }

    public function states() {
        return $this->hasMany('App\Models\Location' ,'state_id', 'id');
    }

    public function cities() {
        return $this->hasMany('App\Models\Location' ,'city_id', 'id');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Location' ,'parent_id', 'id');
    }
}
