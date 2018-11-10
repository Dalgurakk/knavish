<?php

namespace App\Http\Controllers;

use App\Exports\ClientHotelExport;
use App\Models\HotelContract;
use App\Models\HotelContractClient;
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
        $columns = array('hotel_contract_clients.id', 'hotel_contract_clients.name', 'hotels.name', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contract_clients.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchValidFrom = Input::get('columns')['3']['search']['value'];
        $searchValidTo = Input::get('columns')['4']['search']['value'];
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
                $query->where('valid_from', '>=', $searchValidFrom);
            });
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $query->whereHas('hotelContract', function ($query) use ($searchValidTo) {
                $query->where('valid_to', '<=', $searchValidTo);
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
            $r->hotelContract->valid_from = $validFrom->format('d.m.Y');
            $r->hotelContract->valid_to = $validTo->format('d.m.Y');

            $status = 0;
            if ($currentDate->greaterThan($validTo))
                $status = 3; //Old
            else if ($currentDate->greaterThanOrEqualTo($validFrom) && $currentDate->lessThanOrEqualTo($validTo))
                $status = 2; //In Progress
            else if ($currentDate->lessThan($validFrom))
                $status = 1; //Waiting

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'hotel' => $r->hotelContract->hotel->name,
                'valid_from' => $r->hotelContract->valid_from,
                'valid_to' => $r->hotelContract->valid_to,
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

    public function readHotel2(Request $request) {
        $request->user()->authorizeRoles(['client']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contract_clients.id', 'hotel_contract_clients.name', 'hotels.name', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contract_clients.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchValidFrom = Input::get('columns')['3']['search']['value'];
        $searchValidTo = Input::get('columns')['4']['search']['value'];
        $contracts = array();

        $query = DB::table('hotel_contract_clients')
            ->select(
                'hotel_contract_clients.id', 'hotel_contract_clients.name', 'hotels.name as hotel', 'hotel_contracts.valid_from',
                'hotel_contracts.valid_to', 'hotel_contract_clients.active')
            ->join('hotel_contracts', 'hotel_contracts.id', '=', 'hotel_contract_clients.hotel_contract_id')
            ->join('hotels', 'hotels.id', '=', 'hotel_contracts.hotel_id')
            ->where('hotel_contract_clients.client_id', Auth::user()->id)
            ->where('hotel_contract_clients.active', '1')
        ;

        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_contract_clients.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchHotel) && $searchHotel != '') {
            $query->where('hotels.name', 'like', '%' . $searchHotel . '%');
        }
        if(isset($searchValidFrom) && $searchValidFrom != '') {
            $validFrom = Carbon::createFromFormat('d.m.Y', $searchValidFrom);
            $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $validTo = Carbon::createFromFormat('d.m.Y', $searchValidTo);
            $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
        }

        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();

        foreach ($result as $r) {
            $query = HotelContractClient::with([
                'hotelContract', 'hotelContract.hotel', 'hotelContract.hotel.hotelChain', 'hotelContract.hotel.country',
                'hotelContract.hotel.state', 'hotelContract.hotel.city', 'hotelContract.roomTypes', 'hotelContract.paxTypes',
                'hotelContract.boardTypes'])
                ->where('id', $r->id)->get();

            $contract = $query[0];

            $currentDate = Carbon::today();
            $validFrom = Carbon::createFromFormat('!Y-m-d', $r->valid_from);
            $validTo = Carbon::createFromFormat('!Y-m-d', $r->valid_to);
            $r->valid_from = $validFrom->format('d.m.Y');
            $r->valid_to = $validTo->format('d.m.Y');

            $status = 0;
            if ($currentDate->greaterThan($validTo))
                $status = 3; //Old
            else if ($currentDate->greaterThanOrEqualTo($validFrom) && $currentDate->lessThanOrEqualTo($validTo))
                $status = 2; //In Progress
            else if ($currentDate->lessThan($validFrom))
                $status = 1; //Waiting

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'hotel' => $r->hotel,
                'valid_from' => $r->valid_from,
                'valid_to' => $r->valid_to,
                'status' => $status,
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
        $priceRate = $clientContract->hotel_contract_market_id;

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
            'priceRates' => function($query) use ($priceRate) {
                $query->where('id', $priceRate);
            },
            'priceRates.settings' => function($query) use ($start, $end, $priceRate) {
                $query
                    ->orderBy('date', 'asc')
                    ->where('date', '>=', $start->format('Y-m-d'))
                    ->where('date', '<=', $end->format('Y-m-d'))
                    ->where('hotel_contract_market_id', $priceRate);
            }
        ])->where('id', $clientContract->hotel_contract_id)->first();

        if ($contract === null) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid contract.';
        }
        else {
            $settings = array();

            if (isset ($contract->priceRates[0])) {
                foreach ($contract->priceRates[0]->settings as $s) {
                    $settings[$s->date] = json_decode($s->settings, true);
                }

                $roomTypes = $contract->roomTypes;
                $rows = $contract->measures;
                $tables = '';

                for ($m = $start; $m->lessThanOrEqualTo($end); $m->addMonth()) {
                    $table =
                        '<div class="portlet box green">' .
                        '<div class="portlet-title porlet-title-setting">' .
                        '<div class="caption caption-setting">' .
                        '<!--i class="fa fa-calendar"></i-->' . $m->format("F Y") . '</div>' .
                        '<div class="tools tools-setting">' .
                        '<!--a href="" class="fullscreen"> </a-->' .
                        '<a href="javascript:;" class="collapse"> </a>' .
                        '</div>' .
                        '</div>' .
                        '<div class="portlet-body" style="padding: 0;">' .
                        '<div class="table-responsive">';

                    for ($r = 0; $r < count($roomTypes); $r++) {
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
                            $table .=
                                '<tr data-row="' . $rows[$v]->id . '">' .
                                '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . strtoupper($rows[$v]->name) . '</td>';

                            $month = $m->format('d.m.Y');
                            $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                            $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();

                            for ($i = $monthStart; $i->lessThanOrEqualTo($monthEnd); $i->addDay()) {
                                $value = '';
                                if (array_key_exists($i->format('Y-m-d'), $settings)) {
                                    $data = $settings[$i->format('Y-m-d')];
                                    if (isset($data[$roomTypes[$r]->id][$rows[$v]->id]))
                                        $value = $data[$roomTypes[$r]->id][$rows[$v]->id];
                                }
                                $table .=
                                    '<td class="column-setting item-setting"' .
                                    'data-date="' . $i->format('Y-m-d') . '" ' .
                                    'data-measure-id="' . $rows[$v]->id . '"' .
                                    'data-room-type-id="' . $roomTypes[$r]->id . '" ' .
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
        $request->user()->authorizeRoles(['client']);

        $settings = array(
            'headerRange' => 'A4:L4',
            'headerText' => 'Hotel Contracts',
            'cellRange' => 'A6:L6'
        );

        $parameters = array(
            'name'=> Input::get('name'),
            'hotel'=> Input::get('hotel'),
            'validFrom'=> Input::get('validFrom'),
            'validTo'=> Input::get('validTo'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new ClientHotelExport($parameters), 'Hotel Contracts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
