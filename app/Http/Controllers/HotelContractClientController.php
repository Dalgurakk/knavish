<?php

namespace App\Http\Controllers;

use App\Exports\HotelContractClientExport;
use App\Models\HotelContract;
use App\Models\HotelContractClient;
use App\Models\HotelContractPrice;
use App\Models\HotelMeasure;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon;
use Maatwebsite\Excel\Facades\Excel;

class HotelContractClientController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Contract',
            1 => 'Client',
            2 => 'Hotel'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuContractClient'] = 'selected';
        $data['submenuContractClientHotel'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('contract.client.hotel.contract')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contract_clients.id', 'hotel_contract_clients.name', 'hotel.location', 'users.username', 'hotel_contract_clients.valid_from', 'hotel_contract_clients.valid_to', 'hotel_contract_clients.active', 'hotel_contract_clients.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchLocation = Input::get('columns')['2']['search']['value'];
        $searchClient = Input::get('columns')['3']['search']['value'];
        $searchValidFrom = Input::get('columns')['4']['search']['value'];
        $searchValidTo = Input::get('columns')['5']['search']['value'];
        $searchActive = Input::get('columns')['7']['search']['value'];
        $contracts = array();

        $query = HotelContractClient::with([
            'hotelContract',
            'hotelContract.priceRates',
            'hotelContract.priceRates.market',
            'client',
            'priceRate',
            'priceRate.market',
            'hotelContract.hotel',
            'hotelContract.hotel.hotelChain',
            'hotelContract.hotel.country',
            'hotelContract.hotel.state',
            'hotelContract.hotel.city',
            'hotelContract.roomTypes',
            'hotelContract.paxTypes',
            'hotelContract.boardTypes'
        ]);

        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_contract_clients.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchClient) && $searchClient != '') {
            $query->whereHas('client', function ($query) use ($searchClient) {
                $query->where('name', 'like', '%' . $searchClient . '%');
            });
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_contract_clients.active', '=', $searchActive);
        }
        if(isset($searchValidFrom) && $searchValidFrom != '') {
            $query->whereHas('hotelContract', function ($query) use ($searchValidFrom) {
                $validFrom = Carbon::createFromFormat('d.m.Y', $searchValidFrom);
                $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
            });
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $query->whereHas('hotelContract', function ($query) use ($searchValidTo) {
                $validTo = Carbon::createFromFormat('d.m.Y', $searchValidTo);
                $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
            });
        }
        if(isset($searchLocation) && $searchLocation != '') {
            $locations = Location::where('name', 'like', '%' . $searchLocation . '%')->get();
            $allLocationIds = array();
            foreach ($locations as $location) {
                $allLocations = Location::descendantsAndSelf($location->id);
                foreach ($allLocations as $aux) {
                    $allLocationIds[] = $aux->id;
                }
            }
            $query->whereHas('hotelContract.hotel', function ($query) use ($allLocationIds) {
                $query
                    ->whereHas('country', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    })
                    ->orWhereHas('state', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    })
                    ->orWhereHas('city', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    });
            });
        }

        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();

        foreach ($result as $r) {
            $currentDate = Carbon::today();
            $validFrom = Carbon::createFromFormat('!Y-m-d', $r->hotelContract->valid_from);
            $validTo = Carbon::createFromFormat('!Y-m-d', $r->hotelContract->valid_to);
            $r->valid_from = $validFrom->format('d.m.Y');
            $r->valid_to = $validTo->format('d.m.Y');

            $status = 0;
            if ($currentDate->greaterThan($validTo))
                $status = 3; //Old
            else if ($currentDate->greaterThanOrEqualTo($validFrom) && $currentDate->lessThanOrEqualTo($validTo))
                $status = 2; //In Progress
            else if ($currentDate->lessThan($validFrom))
                $status = 1; //Waiting

            $location = 'Undefined';
            $hotel = $r->hotelContract->hotel;
            if (isset($hotel->city->name) && $hotel->city->name != null)
                $location = $hotel->city->name;
            else if (isset($hotel->state->name) && $hotel->state->name != null)
                $location = $hotel->state->name;
            else if (isset($hotel->country->name) && $hotel->country->name != null)
                $location = $hotel->country->name;

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'location' => $location,
                'client' => $r->client->name,
                'valid_from' => $r->valid_from,
                'valid_to' => $r->valid_to,
                'active' => $r->active,
                'status' => $status,
                'object' => $r
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
            'client' => 'required',
            'contract' => 'required',
            'price-rate' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contract = new HotelContractClient();
            $contract->name = Input::get('name');
            $contract->hotel_contract_id = Input::get('contract');
            $contract->client_id = Input::get('client');
            $contract->hotel_contract_market_id = Input::get('price-rate');
            $contract->active = Input::get('active') == 1 ? 1 : 0;

            try {
                $contract->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract ' . $contract->name . ' created successfully.';
                $this->response['data'] = $contract;
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
            'id' => 'required',
            'name' => 'required',
            'client-id' => 'required',
            'hotel-contract-id' => 'required',
            'price-rate' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contract = HotelContractClient::find($id);
            $contract->name = Input::get('name');
            $contract->hotel_contract_id = Input::get('hotel-contract-id');
            $contract->client_id = Input::get('client-id');
            $contract->hotel_contract_market_id = Input::get('price-rate');

            $info = '.';
            if (Input::get('active') == 1) {
                $hotelContract = $contract->hotelContract;
                if ($hotelContract->active == 1)
                    $contract->active = 1;
                else {
                    $contract->active = 0;
                    $info = ' but can not be enabled because the provider contract is disabled.';
                }
            }
            else $contract->active = false;

            try {
                $contract->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract updated successfully' . $info;
                $this->response['data'] = $contract;
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
        $contract = HotelContractClient::find($id);

        try {
            $contract->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Contract ' . $contract->name . ' deleted successfully.';
            $this->response['data'] = $contract;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function settings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Contract',
            1 => 'Client',
            2 => 'Hotel'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuContractClient'] = 'selected';
        $data['submenuContractClientHotel'] = 'selected';
        $data['breadcrumb'] = $breadcrumb;
        $data['contract_id'] = null;
        $data['currentDate'] = parent::currentDate();

        $id = Input::get('id');
        if ($id != '') {
            $data['contract_id'] = $id;
        }
        return view('contract.client.hotel.setting')->with($data);
    }

    public function getContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('contractId');

        $query = HotelContractClient::with([
            'hotelContract',
            'hotelContract.hotel',
            'client',
            'hotelContract.roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'hotelContract.measures' => function($query) {
                $query->orderBy('id', 'asc');
            }
        ]);

        if($id != '') {
            $query->where('id', $id);
        }
        try {
            $contract = $query->first();
            if ($contract === null) {
                $this->response['status'] = 'error';
                $this->response['data'] = null;
                $this->response['message'] = 'Contract not available.';
            }
            else
                $this->response['data'] = $contract;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function getByName(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $string = '%' . Input::get('q') . '%';
        $contracts = HotelContractClient::with([
            'hotelContract',
            'hotelContract.hotel',
            'client',
            'hotelContract.roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'hotelContract.measures' => function($query) {
                $query->orderBy('id', 'asc');
            }
        ])
            ->where('name', 'like', $string)
            ->orderBy('name', 'asc')
            ->get();
        echo json_encode($contracts);
    }

    public function settingsByContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $from = Input::get('from');
        $to = Input::get('to');
        $roomTypes = json_decode(Input::get('rooms'));
        $measures = json_decode(Input::get('rows'));
        $start = Carbon::createFromFormat('d.m.Y', $from)->startOfMonth();
        $end = Carbon::createFromFormat('d.m.Y', $to)->endOfMonth();

        $clientContract = HotelContractClient::with('priceRate')->where('id', $id)->first();
        $market = $clientContract->priceRate->market_id;

        $contract = HotelContract::with([
            'measures' => function($query) use ($measures) {
                $query
                    ->whereIn('hotel_measure_id', $measures)
                    ->orderBy('id', 'asc');
            },
            'roomTypes' => function($query) use ($roomTypes) {
                $query
                    ->whereIn('hotel_room_type_id', $roomTypes)
                    ->orderBy('name', 'asc');
            },
            'settings' => function($query) use ($start, $end){
                $query
                    ->orderBy('date', 'asc')
                    ->where('date', '>=', $start->format('Y-m-d'))
                    ->where('date', '<=', $end->format('Y-m-d'));
            },
            'settings.prices' => function($query) use ($market) {
                $query->where('market_id', $market);
            }
        ])->where('id', $clientContract->hotel_contract_id)->first();

        if ($contract === null) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid contract.';
        }
        else {
            $validFrom = Carbon::createFromFormat('!Y-m-d', $contract->valid_from);
            $validTo = Carbon::createFromFormat('!Y-m-d', $contract->valid_to);

            $settings = array();
            if (isset ($contract->priceRates[0])) {
                foreach ($contract->settings as $setting) {
                    if (isset($setting->prices[0])) {
                        $object = $setting->prices[0];
                    }
                    else {
                        $object = new HotelContractPrice();
                    }
                    $object->allotment = $setting->allotment;
                    $object->release = $setting->release;
                    $object->stop_sale = $setting->stop_sale;
                    $settings[$setting->date][$setting->hotel_room_type_id] = $object;
                }

                $roomTypes = $contract->roomTypes;
                $tables = '';

                for ($m = $start; $m->lessThanOrEqualTo($end); $m->addMonth()) {
                    $table =
                        '<div class="portlet box green">' .
                        '<div class="portlet-title porlet-title-setting">' .
                        '<div class="caption caption-setting">' .
                        /*<i class="fa fa-calendar"></i>' .*/
                        $m->format("F Y") . ' - ' . $contract->markets[0]->name . '</div>' .
                        '<div class="tools tools-setting">' .
                        /*'<a href="" class="fullscreen"> </a>' .*/
                        '<a href="javascript:;" class="collapse"> </a>' .
                        '</div>' .
                        '</div>' .
                        '<div class="portlet-body" style="padding: 0;">' .
                        '<div class="table-responsive">';

                    for ($r = 0; $r < count($roomTypes); $r++) {
                        $rows = $contract->measures;
                        if ($roomTypes[$r]->max_children > 0 && $roomTypes[$r]->max_children < 5) {
                            $measures = $rows;
                            $rows = array();
                            foreach ($measures as $measure) {
                                $rows[] = $measure;
                                if ($measure->code == 'cost') {
                                    for ($x = 1; $x <= $roomTypes[$r]->max_children; $x ++) {
                                        $newMeasure = new HotelMeasure();
                                        $newMeasure->id = 1000 + $x;
                                        $newMeasure->name = 'Cost CH ' . $x;
                                        $newMeasure->active = 1;
                                        $newMeasure->code = 'cost_children_' . $x;
                                        $rows[] = $newMeasure;
                                    }
                                }
                                else if ($measure->code == 'price') {
                                    for ($x = 1; $x <= $roomTypes[$r]->max_children; $x ++) {
                                        $newMeasure = new HotelMeasure();
                                        $newMeasure->id = 2000 + $x;
                                        $newMeasure->name = 'price CH ' . $x;
                                        $newMeasure->active = 1;
                                        $newMeasure->code = 'price_children_' . $x;
                                        $rows[] = $newMeasure;
                                    }
                                }
                            }
                        }
                        $table .=
                            '<table class="table table-striped table-bordered table-setting" data-room="' . $roomTypes[$r]->id . '">' .
                            '<thead>' .
                            '<tr>' .
                            '<th class="room-name head-setting">' . strtoupper($roomTypes[$r]->name) . '</th>';

                        $month = $m->format('d.m.Y');
                        $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                        $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();
                        $count = 0;

                        for ($d = $monthStart; $d->lessThanOrEqualTo($monthEnd); $d->addDay()) {
                            $table .=
                                '<th class="column-setting head-setting">' . $d->format("d") . '</th>';
                            $count ++;
                        }
                        if ($count < 31) {
                            for ($z = $count; $z < 31; $z++) {
                                $table .=
                                    '<th class="column-setting head-setting-invalid"></th>';
                            }
                        }
                        $table .=
                            '</tr>' .
                            '</thead>' .
                            '<tbody>';

                        for ($v = 0; $v < count($rows); $v++) {
                            if ($rows[$v]->code == 'cost' || $rows[$v]->code == 'price') {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '">' .
                                    '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name . ' Ad') . '</td>';
                            }
                            else {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '">' .
                                    '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name) . '</td>';
                            }
                            $month = $m->format('d.m.Y');
                            $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                            $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();

                            for ($i = $monthStart; $i->lessThanOrEqualTo($monthEnd); $i->addDay()) {
                                $value = '';
                                $usableClass = 'item-disabled';
                                if ($validFrom->lessThanOrEqualTo($i) && $validTo->greaterThanOrEqualTo($i)) {
                                    $usableClass = 'item-setting';
                                    if (array_key_exists($i->format('Y-m-d'), $settings)) {
                                        $data = $settings[$i->format('Y-m-d')];
                                        if (isset($data[$roomTypes[$r]->id])) {
                                            $object = $data[$roomTypes[$r]->id];
                                            if ($rows[$v]->code == 'cost') $value = $object->cost_adult;
                                            else if ($rows[$v]->code == 'cost_children_1') $value = $object->cost_children_1;
                                            else if ($rows[$v]->code == 'cost_children_2') $value = $object->cost_children_2;
                                            else if ($rows[$v]->code == 'cost_children_3') $value = $object->cost_children_3;
                                            else if ($rows[$v]->code == 'cost_children_4') $value = $object->cost_children_4;
                                            else if ($rows[$v]->code == 'cost_children_5') $value = $object->cost_children_5;
                                            else if ($rows[$v]->code == 'price') $value = $object->price_adult;
                                            else if ($rows[$v]->code == 'price_children_1') $value = $object->price_children_1;
                                            else if ($rows[$v]->code == 'price_children_2') $value = $object->price_children_2;
                                            else if ($rows[$v]->code == 'price_children_3') $value = $object->price_children_3;
                                            else if ($rows[$v]->code == 'price_children_4') $value = $object->price_children_4;
                                            else if ($rows[$v]->code == 'price_children_5') $value = $object->price_children_5;
                                            else if ($rows[$v]->code == 'allotment') $value = $object->allotment;
                                            else if ($rows[$v]->code == 'release') $value = $object->release;
                                            else if ($rows[$v]->code == 'stop_sale') $value = $object->stop_sale == 1 ? 'X' : '';
                                        }
                                    }
                                }
                                $table .=
                                    '<td class="column-setting ' . $usableClass . '" ' .
                                    'data-date="' . $i->format('Y-m-d') . '" ' .
                                    'data-measure-id="' . $rows[$v]->id . '"' .
                                    'data-room-type-id="' . $roomTypes[$r]->id . '" ' .
                                    'data-market-id="' . $market . '" ' .
                                    '>' . $value . '</td>';
                            }
                            $table .=
                                '</tr>';
                        }
                        $table .=
                            '</tbody>' .
                            '</table>';
                    }
                    $table .=
                        '</div>' .
                        '</div>' .
                        '</div>';
                    $tables .= $table;
                }
                $this->response['status'] = 'success';
                $this->response['from'] = $from;
                $this->response['to'] = $to;
                $this->response['table'] = $tables;
            }
            else {
                $this->response['status'] = 'error';
                $this->response['message'] = 'No data available for this request.';
            }
        }
        echo json_encode($this->response);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $settings = array(
            'headerRange' => 'A4:N4',
            'headerText' => 'Hotel Contracts',
            'cellRange' => 'A6:N6'
        );

        $parameters = array(
            'name'=> Input::get('name'),
            'location'=> Input::get('location'),
            'client'=> Input::get('client'),
            'validFrom'=> Input::get('validFrom'),
            'validTo'=> Input::get('validTo'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new HotelContractClientExport($parameters), 'Hotel Contracts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
