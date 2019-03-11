<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function currentDate() {
        return Carbon::now()->format('d.m.Y');
    }

    function getRealQuery($query, $dumpIt = false)
    {
        $params = array_map(function ($item) {
            return "'{$item}'";
        }, $query->getBindings());
        $result = str_replace_array('\?', $params, $query->toSql());
        if ($dumpIt) {
            dd($result);
        }
        return $result;
    }
}
