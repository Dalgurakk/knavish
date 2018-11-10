<?php

namespace App\Http\Controllers;

use App\Exports\BoardTypeExport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\HotelBoardType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class HotelBoardTypeController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Board Type'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuHotelBoardType'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('hotel.boardtype')->with($data);
    }

    public function read2(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $boardTypes = DB::table('hotel_board_types')
            ->select('hotel_board_types.id', 'hotel_board_types.code', 'hotel_board_types.name', 'hotel_board_types.description', 'hotel_board_types.active')
            ->get();

        return DataTables::of($boardTypes)->make(true);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_board_types.id', 'hotel_board_types.code', 'hotel_board_types.name', 'hotel_board_types.description', 'hotel_board_types.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchCode = Input::get('columns')['1']['search']['value'];
        $searchName = Input::get('columns')['2']['search']['value'];
        $searchActive = Input::get('columns')['4']['search']['value'];
        $boardTypes = array();

        $query = HotelBoardType::orderBy($columns[$orderBy], $orderDirection);

        if(isset($searchCode) && $searchCode != '') {
            $query->where('hotel_board_types.code', 'like', '%' . $searchCode . '%');
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_board_types.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_board_types.active', '=', $searchActive);
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
                'description' => $r->description,
                'active' => $r->active
            );
            $boardTypes[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $boardTypes
        );
        echo json_encode($data);
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
            $boardType = new HotelBoardType();
            $boardType->code = Input::get('code');
            $boardType->name = Input::get('name');
            $boardType->description = Input::get('description');
            $boardType->active = Input::get('active') == 1 ? true : false;

            try {
                $boardType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Board type ' . $boardType->code . ': ' . $boardType->name . ' created successfully.';
                $this->response['data'] = $boardType;
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
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $boardType = HotelBoardType::find($id);
            $boardType->code = Input::get('code');
            $boardType->name = Input::get('name');
            $boardType->description = Input::get('description');
            $boardType->active = Input::get('active') == 1 ? true : false;

            try {
                $boardType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Board type updated successfully.';
                $this->response['data'] = $boardType;
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
        $boardType = HotelBoardType::find($id);

        try {
            $boardType->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Board type ' . $boardType->code . ': ' . $boardType->name . ' deleted successfully.';
            $this->response['data'] = $boardType;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the board type is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:D4',
            'headerText' => 'Board Types',
            'cellRange' => 'A6:D6'
        );

        $parameters = array(
            'code' => Input::get('code'),
            'name'=> Input::get('name'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new BoardTypeExport($parameters), 'Board Types.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
