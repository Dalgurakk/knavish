<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['commercial', 'administrator']);
        $breadcrumb = array(
            0 => 'Dashboard'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuDashboard'] = 'selected';
        return view('home')->with($data);
    }

    public function rangeCalendar(Request $request) {
        $request->user()->authorizeRoles(['commercial', 'administrator']);

        $breadcrumb = array(
            0 => 'Range Calendar'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuDashboard'] = 'selected';

        //$startDate = date_create("01/01/2018");
        $startDate = "2018-10-15";
        //$endDate = date_create("31/12/2018");
        //$endDate = "2018-01-30";
        $endDate = "2019-11-30";
        //echo date_format($date,"Y/m/d");die;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        return view('rangecalendar')->with($data);
    }
}
