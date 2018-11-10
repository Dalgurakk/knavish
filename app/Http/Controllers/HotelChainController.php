<?php

namespace App\Http\Controllers;

use App\Exports\HotelChainExport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\HotelChain;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HotelChainController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Hotel Chain'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuHotelChain'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('hotel.hotelchain')->with($data);
    }

    public function read2(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $hotelsChain = DB::table('hotel_hotels_chain')
            ->select('hotel_hotels_chain.id', 'hotel_hotels_chain.name', 'hotel_hotels_chain.description', 'hotel_hotels_chain.active')
            ->get();

        return DataTables::of($hotelsChain)->make(true);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_hotels_chain.id', 'hotel_hotels_chain.name', 'hotel_hotels_chain.description', 'hotel_hotels_chain.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchActive = Input::get('columns')['3']['search']['value'];
        $chain = array();

        $query = HotelChain::orderBy($columns[$orderBy], $orderDirection);

        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_hotels_chain.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_hotels_chain.active', '=', $searchActive);
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
            $chain[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $chain
        );
        echo json_encode($data);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $hotelChain = new HotelChain();
            $hotelChain->name = Input::get('name');
            $hotelChain->description = Input::get('description');
            $hotelChain->active = Input::get('active') == 1 ? true : false;

            try {
                $hotelChain->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Hotel chain ' . $hotelChain->name . ' created successfully.';
                $this->response['data'] = $hotelChain;
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
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $hotelChain = HotelChain::find($id);
            $hotelChain->name = Input::get('name');
            $hotelChain->description = Input::get('description');
            $hotelChain->active = Input::get('active') == 1 ? true : false;

            try {
                $hotelChain->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Hotel chain updated successfully.';
                $this->response['data'] = $hotelChain;
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
        $hotelChain = HotelChain::find($id);

        try {
            $hotelChain->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Hotel chain ' . $hotelChain->name . ' deleted successfully.';
            $this->response['data'] = $hotelChain;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the hotel chain is in use.';
            $this->response['errors'] = $e->errorInfo[2];
        }

        echo json_encode($this->response);
    }

    public function actives() {
        $hotelsChain = DB::table('hotel_hotels_chain')
            ->select('hotel_hotels_chain.id', 'hotel_hotels_chain.name', 'hotel_hotels_chain.description', 'hotel_hotels_chain.active')
            ->where('hotel_hotels_chain.active', '=', '1')
            ->orderBy('hotel_hotels_chain.name', 'asc')
            ->get();
        return $hotelsChain;
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:C4',
            'headerText' => 'Hotels Chain',
            'cellRange' => 'A6:C6'
        );

        $parameters = array(
            'name'=> Input::get('name'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new HotelChainExport($parameters), 'Hotels Chain.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
