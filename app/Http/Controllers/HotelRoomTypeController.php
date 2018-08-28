<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelRoomType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class HotelRoomTypeController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Room Type'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuHotelRoomType'] = 'selected';

        return view('hotel.roomtype')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $records = DB::table('hotel_room_types')->count();
        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array(
            'hotel_room_types.id', 'hotel_room_types.code', 'hotel_room_types.name',
            'hotel_room_types.maxpax', 'hotel_room_types.minpax', 'hotel_room_types.minadult',
            'hotel_room_types.minchildren', 'hotel_room_types.maxinfant', 'hotel_room_types.active'
        );
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchCode = Input::get('columns')['1']['search']['value'];
        $searchName = Input::get('columns')['2']['search']['value'];
        $searchActive = Input::get('columns')['8']['search']['value'];

        $query = DB::table('hotel_room_types')
            ->select(
                'hotel_room_types.id', 'hotel_room_types.code', 'hotel_room_types.name',
                'hotel_room_types.maxpax', 'hotel_room_types.minpax', 'hotel_room_types.minadult',
                'hotel_room_types.minchildren', 'hotel_room_types.maxinfant', 'hotel_room_types.active');

        if(isset($searchCode) && $searchCode != '') {
            $query->where('hotel_room_types.code', 'like', '%' . $searchCode . '%');
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_room_types.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_room_types.active', '=', $searchActive);
        }
        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $roomTypes = $query->get();

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $roomTypes
        );
        echo json_encode($data);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'code' => 'required',
            'name' => 'required',
            'maxpax' => 'required|integer|min:1',
            'minpax' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minadult' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minchildren' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxinfant' => 'required|integer|min:0|max:' . Input::get('maxpax')
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $roomType = new HotelRoomType();
            $roomType->code = Input::get('code');
            $roomType->name = Input::get('name');
            $roomType->description = null;
            $roomType->maxpax = Input::get('maxpax');
            $roomType->minpax = Input::get('minpax');
            $roomType->minadult = Input::get('minadult');
            $roomType->minchildren = Input::get('minchildren');
            $roomType->maxinfant = Input::get('maxinfant');
            $roomType->active = Input::get('active') == 1 ? true : false;

            try {
                $roomType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Room type ' . $roomType->code . ' - ' . $roomType->name . ' created successfully.';
                $this->response['data'] = $roomType;
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
            'maxpax' => 'required|integer|min:1',
            'minpax' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minadult' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minchildren' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxinfant' => 'required|integer|min:0|max:' . Input::get('maxpax')
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $roomType = HotelRoomType::find($id);

            $roomType->code = Input::get('code');
            $roomType->name = Input::get('name');
            $roomType->description = null;
            $roomType->maxpax = Input::get('maxpax');
            $roomType->minpax = Input::get('minpax');
            $roomType->minadult = Input::get('minadult');
            $roomType->minchildren = Input::get('minchildren');
            $roomType->maxinfant = Input::get('maxinfant');
            $roomType->active = Input::get('active') == 1 ? true : false;

            try {
                $roomType->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Room type updated successfully.';
                $this->response['data'] = $roomType;
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
        $roomType = HotelRoomType::find($id);

        try {
            $roomType->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Room type ' . $roomType->code . ': ' . $roomType->name . ' deleted successfully.';
            $this->response['data'] = $roomType;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the room type is in use.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }

    public function duplicate(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $roomType = HotelRoomType::find($id);
        $new = $roomType->replicate();
        $new->name = $new->name . ' DUPLICATED!';

        try {
            $new->save();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Room type ' . $roomType->code . ': ' . $roomType->name . ' duplicated successfully.';
            $this->response['data'] = $new;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }

    public function getActivesByCodeOrName(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $name = '%' . Input::get('q') . '%';
        $roomTypes = HotelRoomType::where('hotel_room_types.active', '=', '1')
            ->where('hotel_room_types.code', 'like', $name)
            ->orWhere('hotel_room_types.name', 'like', $name)
            ->orderBy('hotel_room_types.code', 'asc')
            ->get();
        echo json_encode($roomTypes);
    }
}
