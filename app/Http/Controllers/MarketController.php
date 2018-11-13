<?php

namespace App\Http\Controllers;

use App\Exports\MarketExport;
use Illuminate\Http\Request;
use App\Models\Market;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MarketController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

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
        $request->user()->authorizeRoles(['administrator']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('markets.id', 'markets.code', 'markets.name', 'markets.description', 'markets.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchCode = Input::get('columns')['1']['search']['value'];
        $searchName = Input::get('columns')['2']['search']['value'];
        $searchActive = Input::get('columns')['4']['search']['value'];
        $markets = array();

        $query = Market::where('id', '>', '1');

        if(isset($searchCode) && $searchCode != '') {
            $query->where('markets.code', 'like', '%' . $searchCode . '%');
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('markets.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('markets.active', '=', $searchActive);
        }

        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);
        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'code' => $r->code,
                'name' => $r->name,
                'description' => $r->description,
                'active' => $r->active
            );
            $markets[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $markets
        );
        echo json_encode($data);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

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
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Something was wrong, please contact the system administrator.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        echo json_encode($this->response);
    }

    public function update(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

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
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Something was wrong, please contact the system administrator.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        echo json_encode($this->response);
    }

    public function delete(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $id = Input::get('id');
        $market = Market::find($id);

        try {
            $market->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Price Rate ' . $market->name . ' deleted successfully.';
            $this->response['data'] = $market;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the price rate is in use.';
            $this->response['errors'] = $e->getMessage();
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
