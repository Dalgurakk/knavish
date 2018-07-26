<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ManageHotelController extends Controller
{
    private $response;
    private $hotelController;

    public function __construct() {
        $this->middleware('auth');
        $this->hotelController = new HotelController();
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Manage',
            1 => 'Hotel'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuManage'] = 'selected';
        $data['submenuManageHotel'] = 'selected';

        return view('manage.hotel')->with($data);
    }

    public function index2(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Manage',
            1 => 'Hotel'
        );

        $hotels = $this->hotelController->actives();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuManage'] = 'selected';
        $data['submenuManageHotel'] = 'selected';
        $data['hotels'] = $hotels;

        return view('manage.hotel')->with($data);
    }

    public function rangeCalendar(Request $request) {
        $request->user()->authorizeRoles(['commercial', 'administrator']);

        $breadcrumb = array(
            0 => 'Range Calendar'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuManage'] = 'selected';
        $data['submenuRangeCalendar'] = 'selected';

        //$startDate = date_create("01/01/2018");
        $startDate = "2018-10-15";
        //$endDate = date_create("31/12/2018");
        //$endDate = "2018-01-30";
        $endDate = "2019-11-30";
        //echo date_format($date,"Y/m/d");die;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        return view('manage.rangecalendar')->with($data);
    }
}
