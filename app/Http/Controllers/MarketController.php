<?php

namespace App\Http\Controllers;

use App\Exports\MarketExport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Market;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MarketController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Administration',
            1 => 'Price Rates'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuMarket'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('administration.market')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $markets = DB::table('markets')
            ->select('markets.id', 'markets.code', 'markets.name', 'markets.description', 'markets.active')
            ->where('markets.id', '>', '1')
            ->get();

        return DataTables::of($markets)->make(true);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'code' => 'required',
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $market = new Market();
            $market->code = Input::get('code');
            $market->name = Input::get('name');
            $market->description = Input::get('description');
            $market->active = Input::get('active') == 1 ? true : false;

            try {
                $market->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Price Rate ' . $market->code . ': ' . $market->name . ' created successfully.';
                $this->response['data'] = $market;
            }
            catch (QueryException $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error.';
                $this->response['errors'] = $e->errorInfo[2];
            }
        }
        echo json_encode($this->response);
    }

    public function update(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $rules = array(
            'code' => 'required',
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $market = Market::find($id);
            $market->code = Input::get('code');
            $market->name = Input::get('name');
            $market->description = Input::get('description');
            $market->active = Input::get('active') == 1 ? true : false;

            try {
                $market->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Price Rate updated successfully.';
                $this->response['data'] = $market;
            }
            catch (QueryException $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error.';
                $this->response['errors'] = $e->errorInfo[2];
            }
        }
        echo json_encode($this->response);
    }

    public function delete(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $market = Market::find($id);

        try {
            $market->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Price Rate ' . $market->name . ' deleted successfully.';
            $this->response['data'] = $market;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the price rate is in use.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:D4',
            'headerText' => 'Price Rates',
            'cellRange' => 'A6:D6'
        );

        $parameters = array(
            'code' => Input::get('code'),
            'name'=> Input::get('name'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new MarketExport($parameters), 'Price Rates.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
