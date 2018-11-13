<?php

namespace App\Http\Controllers;

use App\Exports\PaxTypeExport;
use Illuminate\Http\Request;
use App\Models\HotelPaxType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_pax_types.id', 'hotel_pax_types.code', 'hotel_pax_types.name', 'hotel_pax_types.agefrom', 'hotel_pax_types.ageto', 'hotel_pax_types.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchCode = Input::get('columns')['1']['search']['value'];
        $searchName = Input::get('columns')['2']['search']['value'];
        $searchActive = Input::get('columns')['5']['search']['value'];
        $paxTypes = array();

        $query = HotelPaxType::orderBy($columns[$orderBy], $orderDirection);

        if(isset($searchCode) && $searchCode != '') {
            $query->where('hotel_pax_types.code', 'like', '%' . $searchCode . '%');
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_pax_types.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_pax_types.active', '=', $searchActive);
        }

        $records = $query->count();

        $query
            ->offset($offset)
            ->limit($limit);
        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'code' => $r->code,
                'name' => $r->name,
                'agefrom' => $r->agefrom,
                'ageto' => $r->ageto,
                'active' => $r->active,
                'object' => $r
            );
            $paxTypes[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $paxTypes
        );
        echo json_encode($data);
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
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Something was wrong, please contact the system administrator.';
                $this->response['errors'] = $e->getMessage();
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
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Something was wrong, please contact the system administrator.';
                $this->response['errors'] = $e->getMessage();
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
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the pax type is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $settings = array(
            'headerRange' => 'A4:F4',
            'headerText' => 'Pax Types',
            'cellRange' => 'A6:F6'
        );

        $parameters = array(
            'code' => Input::get('code'),
            'name'=> Input::get('name'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new PaxTypeExport($parameters), 'Pax Types.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
