<?php

namespace App\Http\Controllers;

use App\Exports\ClientHotelExport;
use App\Models\HotelContract;
use App\Models\HotelContractClient;
use App\Models\HotelContractPrice;
use App\Models\HotelMeasure;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ClientHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function hotel(Request $request)
    {
        $request->user()->authorizeRoles(['client']);
        $breadcrumb = array(
            0 => 'Contracts',
            1 => 'Hotel'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuHotel'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('client.hotel.contract')->with($data);
    }

    public function readHotel(Request $request) {
        $request->user()->authorizeRoles(['client']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contract_clients.id', 'hotel_contract_clients.name', 'hotels.name', 'hotels.location', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contract_clients.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchLocation = Input::get('columns')['3']['search']['value'];
        $searchValidFrom = Input::get('columns')['4']['search']['value'];
        $searchValidTo = Input::get('columns')['5']['search']['value'];
        $contracts = array();

        $query = HotelContractClient::with([
            'hotelContract',
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
        if(isset($searchHotel) && $searchHotel != '') {
            $query->whereHas('hotelContract.hotel', function ($query) use ($searchHotel) {
                $query->where('name', 'like', '%' . $searchHotel . '%');
            });
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
                'hotel' => $r->hotelContract->hotel->name,
                'location' => $location,
                'valid_from' => $validFrom->format('d.m.Y'),
                'valid_to' => $validTo->format('d.m.Y'),
                'status' => $status,
                'contract' => $r
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

    public function settingsHotel(Request $request) {
        $request->user()->authorizeRoles(['client']);

        $breadcrumb = array(
            0 => 'My Contracts',
            1 => 'Hotel',
            2 => 'Settings'
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
        return view('client.hotel.setting')->with($data);
    }

    public function getContract(Request $request) {
        $request->user()->authorizeRoles(['client']);

        $id = Input::get('contractId');

        $query = HotelContractClient::with([
            'hotelContract',
            'hotelContract.hotel',
            'client',
            'hotelContract.roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'hotelContract.measures' => function($query) {
                $query->whereNotIn('code', ['cost']);
                $query->orderBy('id', 'asc');
            }
        ]);

        $query
            ->where('client_id', Auth::user()->id)
            ->where('active', '1');

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
        $request->user()->authorizeRoles(['client']);

        $string = '%' . Input::get('q') . '%';
        $contracts = HotelContractClient::with([
            'hotelContract',
            'hotelContract.hotel',
            'client',
            'hotelContract.roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'hotelContract.measures' => function($query) {
                $query->whereNotIn('code', ['cost']);
                $query->orderBy('id', 'asc');
            }
        ])
            ->where('name', 'like', $string)
            ->where('client_id', Auth::user()->id)
            ->where('active', '1')
            ->orderBy('name', 'asc')
            ->get();
        echo json_encode($contracts);
    }

    public function settingsHotelData(Request $request) {
        $request->user()->authorizeRoles(['client']);

        $id = Input::get('id');
        $from = Input::get('from');
        $to = Input::get('to');
        $roomTypes = json_decode(Input::get('rooms'));
        $measures = json_decode(Input::get('rows'));
        $start = Carbon::createFromFormat('d.m.Y', $from)->startOfMonth();
        $end = Carbon::createFromFormat('d.m.Y', $to)->endOfMonth();

        $clientContract = HotelContractClient::where('id', $id)
            ->where('client_id', Auth::user()->id)
            ->where('active', '1')->first();
        $market = $clientContract->priceRate->market_id;

        $contract = HotelContract::with([
            'measures' => function($query) use ($measures) {
                $query
                    ->where('code', '<>', 'cost')
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
            },
            'settings.clientSetttings' => function($query) use ($clientContract) {
                $query->where('hotel_contract_client_id', $clientContract->id);
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
            if (isset($contract->priceRates) && count($contract->priceRates) > 0) {
                foreach ($contract->settings as $setting) {
                    $object = null;
                    if (isset($setting->prices[0])) {
                        $object = $setting->prices[0];
                    }
                    else {
                        $object = new HotelContractPrice();
                    }
                    if (isset($setting->clientSetttings) && count($setting->clientSetttings) > 0) {
                        $clientSettting = $setting->clientSetttings[0];
                        if (!is_null($clientSettting->allotment)) {
                            $object->allotment = $clientSettting->allotment;
                        }
                        else {
                            $object->allotment = $setting->allotment;
                        }
                        if (!is_null($clientSettting->allotment_sold) && $clientSettting->allotment_sold > 0) {
                            $object->allotment_sold = $clientSettting->allotment_sold;
                        }
                        else {
                            $object->allotment_sold = 0;
                        }
                        if (!is_null($clientSettting->release)) {
                            $object->release = $clientSettting->release;
                        }
                        else {
                            $object->release = $setting->release;
                        }
                        if (!is_null($clientSettting->stop_sale) && $clientSettting->stop_sale == 1) {
                            $object->stop_sale = $clientSettting->stop_sale;
                        }
                        else {
                            $object->stop_sale = $setting->stop_sale;
                        }
                    }
                    else {
                        $object->allotment = $setting->allotment;
                        $object->allotment_sold = 0;
                        $object->release = $setting->release;
                        $object->stop_sale = $setting->stop_sale;
                    }
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
                        $m->format("F Y") . '</div>' .
                        '<div class="tools tools-setting">' .
                        /*'<a href="" class="fullscreen"> </a>' .*/
                        '<a href="javascript:;" class="collapse"> </a>' .
                        '</div>' .
                        '</div>' .
                        '<div class="portlet-body" style="padding: 0;">' .
                        '<div class="table-responsive">';

                    for ($r = 0; $r < count($roomTypes); $r++) {
                        $rows = $contract->measures;
                        $measures = $rows;
                        $rows = array();
                        foreach ($measures as $measure) {
                            $rows[] = $measure;
                            if ($roomTypes[$r]->max_children > 0 && $roomTypes[$r]->max_children < 3) {
                                if ($measure->code == 'price') {
                                    for ($x = 1; $x <= $roomTypes[$r]->max_children; $x ++) {
                                        $newMeasure = new HotelMeasure();
                                        $newMeasure->id = 2000 + $x;
                                        $newMeasure->name = 'Price CH ' . $x;
                                        $newMeasure->active = 1;
                                        $newMeasure->code = 'price_children_' . $x;
                                        $newMeasure->parent = 'price-' . $roomTypes[$r]->id;
                                        $rows[] = $newMeasure;
                                    }
                                }
                            }
                            if ($measure->code == 'allotment') {
                                $newMeasure = new HotelMeasure();
                                $newMeasure->id = 3001;
                                $newMeasure->name = 'Allot. Sold';
                                $newMeasure->active = 1;
                                $newMeasure->code = 'allotment_sold';
                                $newMeasure->parent = 'allotment-' . $roomTypes[$r]->id;
                                $rows[] = $newMeasure;
                            }
                        }
                        $table .=
                            '<table class="table table-striped table-bordered table-setting" data-room="' . $roomTypes[$r]->id . '">' .
                            '<thead>' .
                            '<tr>' .
                            '<th class="room-name head-setting">' . strtoupper($roomTypes[$r]->code) . ': ' . strtoupper($roomTypes[$r]->name) . '</th>';

                        $month = $m->format('d.m.Y');
                        $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                        $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();
                        $count = 0;

                        for ($d = $monthStart; $d->lessThanOrEqualTo($monthEnd); $d->addDay()) {
                            $isSundayClass = $d->dayOfWeek == 0 ? ' sunday ' : '';
                            $table .=
                                '<th class="column-setting head-setting' . $isSundayClass . '">' . $d->format("D")[0] . $d->format("d") . '</th>';
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
                            if ($rows[$v]->code == 'price') {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '">' .
                                    '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name . ' Ad');
                                if ($roomTypes[$r]->max_children > 0) {
                                    $table .= '<button class="measure-detail btn-default closed" data="' . $rows[$v]->code . '-' . $roomTypes[$r]->id .'" data-measure="' . $rows[$v]->code . '">+</button>';
                                }
                                $table .= '</td>';
                            }
                            else if ($rows[$v]->code == 'allotment') {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '">' .
                                    '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name);
                                $table .= '<button class="measure-detail btn-default closed" data="' . $rows[$v]->code . '-' . $roomTypes[$r]->id .'" data-measure="' . $rows[$v]->code . '">+</button>';
                                $table .= '</td>';
                            }
                            else {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '"';
                                if (isset($rows[$v]->parent)) {
                                    $table .= ' data-parent="' . $rows[$v]->parent . '" class="hidden"';
                                }
                                $table .=
                                    '><td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name) . '</td>';
                            }
                            $month = $m->format('d.m.Y');
                            $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                            $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();

                            for ($i = $monthStart; $i->lessThanOrEqualTo($monthEnd); $i->addDay()) {
                                $value = '';
                                $showValue = '';
                                $usableClass = 'item-disabled';
                                if ($validFrom->lessThanOrEqualTo($i) && $validTo->greaterThanOrEqualTo($i)) {
                                    $usableClass = 'item-setting';
                                    if (array_key_exists($i->format('Y-m-d'), $settings)) {
                                        $data = $settings[$i->format('Y-m-d')];
                                        if (isset($data[$roomTypes[$r]->id])) {
                                            $object = $data[$roomTypes[$r]->id];
                                            if ($rows[$v]->code == 'price') { $value = $object->price_adult; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_1') { $value = $object->price_children_1; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_2') { $value = $object->price_children_2; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_3') { $value = $object->price_children_3; $showValue = $value; }
                                            else if ($rows[$v]->code == 'allotment') { $value = $object->allotment; $showValue = $value; }
                                            else if ($rows[$v]->code == 'allotment_sold') { $value = $object->allotment_sold; $showValue = $value; }
                                            else if ($rows[$v]->code == 'release') { $value = $object->release; $showValue = $value; }
                                            else if ($rows[$v]->code == 'stop_sale') { $value = $object->stop_sale; $showValue = $object->stop_sale == 1 ? '<span class="stop-sales">SS</span>' : ''; }
                                        }
                                    }
                                }
                                $table .=
                                    '<td class="column-setting ' . $usableClass . '" ' .
                                    'data="' . $value . '" ' .
                                    'data-date="' . $i->format('Y-m-d') . '" ' .
                                    'data-measure-id="' . $rows[$v]->id . '"' .
                                    'data-room-type-id="' . $roomTypes[$r]->id . '" ' .
                                    'data-market-id="' . $market . '" ' .
                                    '>' . $showValue . '</td>';
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
        $request->user()->authorizeRoles(['client']);

        $settings = array(
            'headerRange' => 'A4:L4',
            'headerText' => 'Hotel Contracts',
            'cellRange' => 'A6:L6'
        );

        $parameters = array(
            'name'=> Input::get('name'),
            'hotel'=> Input::get('hotel'),
            'location'=> Input::get('location'),
            'validFrom'=> Input::get('validFrom'),
            'validTo'=> Input::get('validTo'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new ClientHotelExport($parameters), 'Hotel Contracts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
