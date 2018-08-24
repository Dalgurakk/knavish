<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Market;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
            1 => 'Markets'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuMarket'] = 'selected';

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
                $this->response['message'] = 'Market ' . $market->code . ': ' . $market->name . ' created successfully.';
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
                $this->response['message'] = 'Market updated successfully.';
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
            $user = DB::table('users')
                ->where('users.market_id', '=', $market->id)
                ->first();
            if ($user === null) {
                $market->delete();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Market ' . $market->code . ': ' . $market->name . ' deleted successfully.';
                $this->response['data'] = $market;
            }
            else {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Market is in use, please check the user: ' . $user->username . '.';
            }
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }
}
