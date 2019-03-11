<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function users()
    {
        return $this
            ->belongsToMany('App\Models\User')
            ->withTimestamps();
    }
}
