<?php

namespace App\Http\Controllers;

use App\Exports\BoardTypeExport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelBoardType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
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

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $boardTypes = DB::table('hotel_board_types')
            ->select('hotel_board_types.id', 'hotel_board_types.code', 'hotel_board_types.name', 'hotel_board_types.description', 'hotel_board_types.active')
            ->get();

        return DataTables::of($boardTypes)->make(true);
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
        $boardType = HotelBoardType::find($id);

        try {
            $boardType->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Board type ' . $boardType->code . ': ' . $boardType->name . ' deleted successfully.';
            $this->response['data'] = $boardType;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the board type is in use.';
            $this->response['errors'] = $e->errorInfo[2];
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
