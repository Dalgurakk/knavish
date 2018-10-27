<?php

namespace App\Http\Controllers;

use App\Exports\RoomTypeExport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelRoomType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
        $data['currentDate'] = parent::currentDate();

        return view('hotel.roomtype')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array(
            'hotel_room_types.id', 'hotel_room_types.code', 'hotel_room_types.name',
            'hotel_room_types.max_pax', 'hotel_room_types.max_adult', 'hotel_room_types.min_adult',
            'hotel_room_types.max_children', 'hotel_room_types.min_children', 'hotel_room_types.max_infant',
            'hotel_room_types.min_infant', 'hotel_room_types.active'
        );
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchCode = Input::get('columns')['1']['search']['value'];
        $searchName = Input::get('columns')['2']['search']['value'];
        $searchActive = Input::get('columns')['10']['search']['value'];

        $query = DB::table('hotel_room_types')
            ->select(
                'hotel_room_types.id', 'hotel_room_types.code', 'hotel_room_types.name',
                'hotel_room_types.max_pax', 'hotel_room_types.max_adult', 'hotel_room_types.min_adult',
                'hotel_room_types.max_children', 'hotel_room_types.min_children', 'hotel_room_types.max_infant',
                'hotel_room_types.min_infant', 'hotel_room_types.active');

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
        $records = count($roomTypes);

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

        $total = Input::get('minadult') + Input::get('minchildren') + Input::get('mininfant');
        $rules = array(
            'code' => 'required',
            'name' => 'required',
            'maxadult' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxchildren' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxinfant' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minadult' => 'required|integer|min:0|max:' . Input::get('maxadult'),
            'minchildren' => 'required|integer|min:0|max:' . Input::get('maxchildren'),
            'mininfant' => 'required|integer|min:0|max:' . Input::get('maxinfant'),
            'maxpax' => 'required|integer|min:1|min:' . $total
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
            $roomType->max_pax = Input::get('maxpax');
            $roomType->max_adult = Input::get('maxadult');
            $roomType->min_adult = Input::get('minadult');
            $roomType->max_children = Input::get('maxchildren');
            $roomType->min_children = Input::get('minchildren');
            $roomType->max_infant = Input::get('maxinfant');
            $roomType->min_infant = Input::get('mininfant');
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
        $total = Input::get('minadult') + Input::get('minchildren') + Input::get('mininfant');
        $rules = array(
            'code' => 'required',
            'name' => 'required',
            'maxadult' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxchildren' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'maxinfant' => 'required|integer|min:0|max:' . Input::get('maxpax'),
            'minadult' => 'required|integer|min:0|max:' . Input::get('maxadult'),
            'minchildren' => 'required|integer|min:0|max:' . Input::get('maxchildren'),
            'mininfant' => 'required|integer|min:0|max:' . Input::get('maxinfant'),
            'maxpax' => 'required|integer|min:1|min:' . $total
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
            $roomType->max_pax = Input::get('maxpax');
            $roomType->max_adult = Input::get('maxadult');
            $roomType->min_adult = Input::get('minadult');
            $roomType->max_children = Input::get('maxchildren');
            $roomType->min_children = Input::get('minchildren');
            $roomType->max_infant = Input::get('maxinfant');
            $roomType->min_infant = Input::get('mininfant');
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

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:K4',
            'headerText' => 'Room Types',
            'cellRange' => 'A6:K6'
        );

        $parameters = array(
            'code' => Input::get('code'),
            'name'=> Input::get('name'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new RoomTypeExport($parameters), 'Room Types.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
