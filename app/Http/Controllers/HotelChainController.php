<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelChain;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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

        return view('hotel.hotelchain')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $hotelsChain = DB::table('hotel_hotels_chain')
            ->select('hotel_hotels_chain.id', 'hotel_hotels_chain.name', 'hotel_hotels_chain.description', 'hotel_hotels_chain.active')
            ->get();

        return DataTables::of($hotelsChain)->make(true);
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
            $hotel = DB::table('hotels')
                ->where('hotels.hotel_chain_id', '=', $hotelChain->id)
                ->first();
            if ($hotel === null) {
                $hotelChain->delete();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Hotel chain ' . $hotelChain->name . ' deleted successfully.';
                $this->response['data'] = $hotelChain;
            }
            else {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Hotel chain is in use, please check the hotel: ' . $hotel->name . '.';
            }
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }

        echo json_encode($this->response);
    }

    public function hotelChainActive() {
        $hotelsChain = DB::table('hotel_hotels_chain')
            ->select('hotel_hotels_chain.id', 'hotel_hotels_chain.name', 'hotel_hotels_chain.description', 'hotel_hotels_chain.active')
            ->where('hotel_hotels_chain.active', '=', '1')
            ->orderBy('hotel_hotels_chain.name', 'asc')
            ->get();
        return $hotelsChain;
    }
}