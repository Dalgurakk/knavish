<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelPaxType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class HotelPaxTypeController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Pax Type'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuHotelPaxType'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('hotel.paxtype')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $paxTypes = DB::table('hotel_pax_types')
            ->select('hotel_pax_types.id', 'hotel_pax_types.code', 'hotel_pax_types.name', 'hotel_pax_types.agefrom', 'hotel_pax_types.ageto', 'hotel_pax_types.active')
            ->get();

        return DataTables::of($paxTypes)->make(true);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'code' => 'required',
            'name' => 'required',
            'agefrom' => 'required|numeric|min:0|max:99',
            'ageto' => 'required|numeric|min:' . (Input::get('agefrom')) . '|max:99'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $paxType = new HotelPaxType();
            $paxType->code = Input::get('code');
            $paxType->name = Input::get('name');
            $paxType->agefrom = Input::get('agefrom');
            $paxType->ageto = Input::get('ageto');
            $paxType->description = null;
            $paxType->active = Input::get('active') == 1 ? true : false;

            try {
                $paxType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Pax type ' . $paxType->code . ': ' . $paxType->name . ' created successfully.';
                $this->response['data'] = $paxType;
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
            'name' => 'required',
            'agefrom' => 'required|numeric|min:0|max:99',
            'ageto' => 'required|numeric|min:' . (Input::get('agefrom')) . '|max:99'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $paxType = HotelPaxType::find($id);
            $paxType->code = Input::get('code');
            $paxType->name = Input::get('name');
            $paxType->agefrom = Input::get('agefrom');
            $paxType->ageto = Input::get('ageto');
            $paxType->active = Input::get('active') == 1 ? true : false;

            try {
                $paxType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Pax type updated successfully.';
                $this->response['data'] = $paxType;
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
        $paxType = HotelPaxType::find($id);

        try {
            $paxType->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Pax type ' . $paxType->code . ': ' . $paxType->name . ' deleted successfully.';
            $this->response['data'] = $paxType;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the pax type is in use.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }
}
