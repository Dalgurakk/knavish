<?php

namespace App\Http\Controllers;

use App\HotelBoardType;
use App\HotelContract;
use App\HotelPaxType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Carbon;

class HotelContractController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Hotels'
        );

        $paxTypes = HotelPaxType::where('active', '1')->get();
        $boardTypes = HotelBoardType::where('active', '1')->get();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuContract'] = 'selected';
        $data['paxTypes'] = $paxTypes;
        $data['boardTypes'] = $boardTypes;

        return view('hotel.contract')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $records = DB::table('hotel_contracts')->count();
        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contracts.id', 'hotel_contracts.name', 'hotels.name', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.hotel_id');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchValidFrom = Input::get('columns')['3']['search']['value'];
        $searchValidTo = Input::get('columns')['4']['search']['value'];
        $searchActive = Input::get('columns')['5']['search']['value'];
        $contracts = array();

        $query = DB::table('hotel_contracts')
            ->select(
                'hotel_contracts.id', 'hotel_contracts.name', 'hotels.name as hotel', 'hotel_contracts.valid_from',
                'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.status')
            ->join('hotels', 'hotels.id', '=', 'hotel_contracts.hotel_id');

        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_contracts.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchHotel) && $searchHotel != '') {
            $query->where('hotels.name', 'like', '%' . $searchHotel . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_contracts.active', '=', $searchActive);
        }
        if(isset($searchValidFrom) && $searchValidFrom != '') {
            $validFrom = Carbon::createFromFormat('d-m-Y', $searchValidFrom);
            $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $validTo = Carbon::createFromFormat('d-m-Y', $searchValidTo);
            $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
        }
        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();

        foreach ($result as $r) {
            $query = HotelContract::with([
                'hotel', 'hotel.hotelChain', 'hotel.country', 'hotel.state', 'hotel.city',
                'roomTypes', 'paxTypes', 'boardTypes'])
                ->where('id', $r->id)->get();

            $contract = $query[0];
            $status_text = '';
            if ($r->status == '0')
                $status_text = 'Draft';
            else if ($r->status == '1')
                $status_text = 'Signed';
            $contract->status_text = $status_text;

            $r->valid_from = Carbon::createFromFormat('Y-m-d', $r->valid_from)->format('d-m-Y');
            $r->valid_to = Carbon::createFromFormat('Y-m-d', $r->valid_to)->format('d-m-Y');

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'hotel' => $r->hotel,
                'valid_from' => $r->valid_from,
                'valid_to' => $r->valid_to,
                'active' => $r->active,
                'contract' => $contract
            );
            $contracts[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $contracts
        );
        echo json_encode($data);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'name' => 'required',
            'hotel-id' => 'required',
            'valid-from' => 'required|date|date_format:"d-m-Y"|before:valid-to',
            'valid-to' => 'required|date|date_format:"d-m-Y"|after:valid-from',
            'hotel' => 'required',
            'status' => 'required',
            'roomTypes' => 'required|json',
            'boardTypes' => 'required|json',
            'paxTypes' => 'required|json'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contract = new HotelContract();
            $contract->name = Input::get('name');
            $contract->hotel_id = Input::get('hotel-id');
            $validFrom = Carbon::createFromFormat('d-m-Y', Input::get('valid-from'));
            $validTo = Carbon::createFromFormat('d-m-Y', Input::get('valid-to'));
            $contract->valid_from = $validFrom->format('Y-m-d');
            $contract->valid_to = $validTo->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? true : false;
            $contract->status = Input::get('status');

            DB::transaction(function() use ($contract){
                try {
                    $contract->save();
                    $roomTypes = json_decode(Input::get('roomTypes'), true);
                    $boardTypes = json_decode(Input::get('boardTypes'));
                    $paxTypes = json_decode(Input::get('paxTypes'));

                    foreach ($roomTypes as $r) {
                        $contract->roomTypes()->attach($r);
                    }
                    foreach ($paxTypes as $p) {
                        $contract->paxTypes()->attach($p);
                    }
                    foreach ($boardTypes as $b) {
                        $contract->boardTypes()->attach($b);
                    }

                    $this->response['status'] = 'success';
                    $this->response['message'] = 'Contract ' . $contract->name . ' created successfully.';
                    $this->response['data'] = $contract;
                }
                catch (QueryException $e) {
                    DB::rollback();
                    $this->response['status'] = 'error';
                    $this->response['message'] = 'Database error.';
                    $this->response['errors'] = $e->errorInfo[2];
                }
            });
        }
        echo json_encode($this->response);
    }

    public function update(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $rules = array(
            'name' => 'required',
            'id' => 'required',
            'hotel-id' => 'required',
            'valid-from' => 'required|date|date_format:"d-m-Y"|before:valid-to',
            'valid-to' => 'required|date|date_format:"d-m-Y"|after:valid-from',
            'status' => 'required',
            'roomTypes' => 'required|json',
            'boardTypes' => 'required|json',
            'paxTypes' => 'required|json'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contract = HotelContract::find($id);
            $contract->name = Input::get('name');
            $contract->hotel_id = Input::get('hotel-id');

            $validFrom = Carbon::createFromFormat('d-m-Y', Input::get('valid-from'));
            $validTo = Carbon::createFromFormat('d-m-Y', Input::get('valid-to'));
            $contract->valid_from = $validFrom->format('Y-m-d');
            $contract->valid_to = $validTo->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? true : false;
            $contract->status = Input::get('status');

            DB::transaction(function() use ($contract){
                try {
                    $contract->save();
                    $roomTypes = json_decode(Input::get('roomTypes'), true);
                    $boardTypes = json_decode(Input::get('boardTypes'));
                    $paxTypes = json_decode(Input::get('paxTypes'));

                    $contract->roomTypes()->sync($roomTypes);
                    $contract->boardTypes()->sync($boardTypes);
                    $contract->paxTypes()->sync($paxTypes);

                    $this->response['status'] = 'success';
                    $this->response['message'] = 'Contract updated successfully.';
                    $this->response['data'] = $contract;
                }
                catch (QueryException $e) {
                    DB::rollback();
                    $this->response['status'] = 'error';
                    $this->response['message'] = 'Database error.';
                    $this->response['errors'] = $e->errorInfo[2];
                }
            });
        }
        echo json_encode($this->response);
    }

    public function delete(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $contract = HotelContract::find($id);

        DB::transaction(function() use ($contract){
            try {
                $contract->paxTypes()->detach();
                $contract->boardTypes()->detach();
                $contract->roomTypes()->detach();
                $contract->delete();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract ' . $contract->name . ' deleted successfully.';
                $this->response['data'] = $contract;
            }
            catch (QueryException $e) {
                DB::rollback();
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error.';
                $this->response['errors'] = $e->errorInfo[2];
            }
        });
        echo json_encode($this->response);
    }

    public function settings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'id' => 'required',
            'from' => 'required|date',
            'to' => 'required|date'
            //'to' => 'required|date|before:from'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid parameters.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $id = Input::get('id');
            $from = Carbon::createFromFormat('d-m-Y', Input::get('from'));
            $to = Carbon::createFromFormat('d-m-Y', Input::get('to'));

            /*$from = Input::get('from');
            $to = Input::get('to');*/
            $contract = HotelContract::find($id);

            if ($contract === null) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Invalid contract.';
                $this->response['errors'] = $validator->errors();
            }
            else {
                //$settings = $contract->settings;
                $settings_json = '
                    [
                        {
                            "date": "2018-01-01",
                            "price": "52",
                            "allotment": "5",
                            "release": "7",
                            "offer": "2",
                            "stop_sale": "0",
                            "restriction": "0",
                            "supplement":  "0"
                        },
                        {
                            "date": "2018-01-02",
                            "price": "52",
                            "allotment": "5",
                            "release": "7",
                            "offer": "2",
                            "stop_sale": "0",
                            "restriction": "0",
                            "supplement":  "0"
                        },
                        {
                            "date": "2018-01-03",
                            "price": "52",
                            "allotment": "5",
                            "release": "7",
                            "offer": "2",
                            "stop_sale": "0",
                            "restriction": "0",
                            "supplement":  "0"
                        }
                    ]';
                $settings = json_decode($settings_json);

                //$settings = new \stdClass();
                //$settings->data = array(

                //);
                //$temp = $settings->data;

                //print_r($settings);die;
                $this->response['status'] = 'success';
                $this->response['from'] = $from->format('d-m-Y');
                $this->response['to'] = $to->format('d-m-Y');
                $this->response['data'] = $settings;
            }
        }
        echo json_encode($this->response);
    }
}
