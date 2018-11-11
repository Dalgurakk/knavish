<?php

namespace App\Http\Controllers;

use App\Exports\HotelContractExport;
use App\Models\HotelBoardType;
use App\Models\HotelContract;
use App\Models\HotelContractMarket;
use App\Models\HotelContractSetting;
use App\Models\HotelMeasure;
use App\Models\HotelPaxType;
use App\Models\Market;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Exceptions\Exception;

class HotelContractController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Contract',
            1 => 'Provider',
            2 => 'Hotel'
        );

        $paxTypes = HotelPaxType::where('active', '1')->get();
        $boardTypes = HotelBoardType::where('active', '1')->get();
        //$measures = HotelMeasure::where('active', '1')->orderBy('id', 'asc')->get();
        $measures = HotelMeasure::where('active', '1')->whereIn('code', ['cost', 'price'])->orderBy('id', 'asc')->get();
        $markets = Market::where('active', '1')->get();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuContractProvider'] = 'selected';
        $data['submenuContractProviderHotel'] = 'selected';
        $data['paxTypes'] = $paxTypes;
        $data['boardTypes'] = $boardTypes;
        $data['measures'] = $measures;
        $data['markets'] = $markets;
        $data['currentDate'] = parent::currentDate();

        return view('contract.provider.hotel.contract')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contracts.id', 'hotel_contracts.name', 'hotels.name', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.active', 'hotel_contracts.hotel_id');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchValidFrom = Input::get('columns')['3']['search']['value'];
        $searchValidTo = Input::get('columns')['4']['search']['value'];
        $searchActive = Input::get('columns')['6']['search']['value'];
        $contracts = array();

        $query = HotelContract::with([
            'hotel',
            'hotel.hotelChain',
            'hotel.country',
            'hotel.state',
            'hotel.city',
            'roomTypes',
            'paxTypes',
            'boardTypes',
            'markets',
            'measures'
        ]);
        if(isset($searchName) && $searchName != '') {
            $query->where('hotel_contracts.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotel_contracts.active', '=', $searchActive);
        }
        if(isset($searchValidFrom) && $searchValidFrom != '') {
            $validFrom = Carbon::createFromFormat('d.m.Y', $searchValidFrom);
            $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $validTo = Carbon::createFromFormat('d.m.Y', $searchValidTo);
            $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
        }
        if(isset($searchHotel) && $searchHotel != '') {
            $query->whereHas('hotel', function ($query) use ($searchHotel) {
                $query->where('name', 'like', '%' . $searchHotel . '%');
            });
        }

        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);
        $result = $query->get();

        foreach ($result as $r) {
            $contract = $r;
            $currentDate = Carbon::today();
            $validFrom = Carbon::createFromFormat('!Y-m-d', $r->valid_from);
            $validTo = Carbon::createFromFormat('!Y-m-d', $r->valid_to);

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
                'hotel' => $r->hotel->name,
                'valid_from' => $validFrom->format('d.m.Y'),
                'valid_to' => $validTo->format('d.m.Y'),
                'active' => $r->active,
                'status' => $status,
                'object' => $contract
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

    public function read2(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_contracts.id', 'hotel_contracts.name', 'hotels.name', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.active', 'hotel_contracts.hotel_id');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchValidFrom = Input::get('columns')['3']['search']['value'];
        $searchValidTo = Input::get('columns')['4']['search']['value'];
        $searchActive = Input::get('columns')['6']['search']['value'];
        $contracts = array();

        $query = DB::table('hotel_contracts')
            ->select(
                'hotel_contracts.id', 'hotel_contracts.name', 'hotels.name as hotel', 'hotel_contracts.valid_from',
                'hotel_contracts.valid_to', 'hotel_contracts.active')
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
            $validFrom = Carbon::createFromFormat('d.m.Y', $searchValidFrom);
            $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
        }
        if(isset($searchValidTo) && $searchValidTo != '') {
            $validTo = Carbon::createFromFormat('d.m.Y', $searchValidTo);
            $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
        }
        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();
        $records = count($result);

        foreach ($result as $r) {
            $query = HotelContract::with([
                'hotel', 'hotel.hotelChain', 'hotel.country', 'hotel.state', 'hotel.city',
                'roomTypes', 'paxTypes', 'boardTypes', 'markets', 'measures'])
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
                'active' => $r->active,
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

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'name' => 'required',
            'hotel-id' => 'required',
            'valid-from' => 'required|date|date_format:"d.m.Y"|before:valid-to',
            'valid-to' => 'required|date|date_format:"d.m.Y"|after:valid-from',
            'hotel' => 'required',
            'roomTypes' => 'required|json',
            'boardTypes' => 'required|json',
            'paxTypes' => 'required|json',
            'markets' => 'required|json'
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
            $contract->valid_from = Carbon::createFromFormat('!d.m.Y', Input::get('valid-from'))->format('Y-m-d');
            $contract->valid_to = Carbon::createFromFormat('!d.m.Y', Input::get('valid-to'))->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? true : false;
            $contract->status = Input::get('status');

            DB::beginTransaction();
            try {
                $contract->save();
                $roomTypes = json_decode(Input::get('roomTypes'), true);
                $boardTypes = json_decode(Input::get('boardTypes'));
                $paxTypes = json_decode(Input::get('paxTypes'));
                $markets = json_decode(Input::get('markets'));
                $temp = json_decode(Input::get('measures'));
                $measures = array(1, 2);
                foreach ($temp as $key => $val) {
                    if($val == 1 || $val == 2) continue;
                    else $measures[] = $val;
                }
                foreach ($roomTypes as $r) {
                    $contract->roomTypes()->attach($r);
                }
                foreach ($paxTypes as $p) {
                    $contract->paxTypes()->attach($p);
                }
                foreach ($boardTypes as $b) {
                    $contract->boardTypes()->attach($b);
                }
                foreach ($measures as $m) {
                    $contract->measures()->attach($m);
                }
                foreach ($markets as $k) {
                    $contract->markets()->attach($k->market_id, [
                        'type' => $k->rate_type,
                        'value' => $k->value,
                        'round' => $k->round_type
                    ]);
                }
                DB::commit();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract ' . $contract->name . ' created successfully.';
                $this->response['data'] = $contract;
            }
            catch (\Exception $e) {
                DB::rollBack();
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
            'name' => 'required',
            'id' => 'required',
            'hotel-id' => 'required',
            'valid-from' => 'required|date|date_format:"d.m.Y"|before:valid-to',
            'valid-to' => 'required|date|date_format:"d.m.Y"|after:valid-from',
            'roomTypes' => 'required|json',
            'boardTypes' => 'required|json',
            'paxTypes' => 'required|json',
            'measures' => 'required|json',
            'markets' => 'required|json'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contract = HotelContract::with(['priceRates', 'priceRates.settings'])->find($id);
            $contract->name = Input::get('name');
            $contract->hotel_id = Input::get('hotel-id');
            $contract->valid_from = Carbon::createFromFormat('d.m.Y', Input::get('valid-from'))->format('Y-m-d');
            $contract->valid_to = Carbon::createFromFormat('d.m.Y', Input::get('valid-to'))->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? true : false;
            $contract->status = Input::get('status');

            DB::beginTransaction();
            try {
                $roomTypes = json_decode(Input::get('roomTypes'), true);
                $boardTypes = json_decode(Input::get('boardTypes'));
                $markets = json_decode(Input::get('markets'));
                $paxTypes = json_decode(Input::get('paxTypes'));
                $temp = json_decode(Input::get('measures'));
                $unusedRooms = array();

                foreach ($contract->roomTypes as $contractRoom) {
                    $flag = false;
                    foreach($roomTypes as $roomtype) {
                        if($roomtype == $contractRoom->id) {
                            $flag = true;
                            break;
                        }
                    }
                    if (!$flag) {
                        $unusedRooms[] = $contractRoom;
                    }
                }

                $measures = array(1, 2);
                foreach ($temp as $key => $val) {
                    if($val == 1 || $val == 2) continue;
                    else $measures[] = $val;
                }

                $syncMarkets = array();
                foreach ($markets as $k) {
                    $syncMarkets[$k->market_id] = array(
                        'type' => $k->rate_type,
                        'value' => $k->value,
                        'round' => $k->round_type
                    );

                    if ($k->update_price == '1') {
                        foreach ($contract->priceRates as $r) {
                            if ($k->market_id == $r->market_id) {
                                if ($k->rate_type != $r->type || $k->value != $r->value || $k->round_type != $r->round) {
                                    foreach ($r->settings as $s) {
                                        $settings = json_decode($s->settings, true);
                                        foreach($settings as $key => $data){
                                            $aux = $r;
                                            $aux->type = $k->rate_type;
                                            $aux->value = $k->value;
                                            $aux->round = $k->round_type;
                                            $data['2'] = $this->calculatePrice($data['1'], $aux);
                                            $settings[$key] = $data;
                                        }
                                        $s->settings = json_encode($settings);
                                        $s->save();
                                    }
                                }
                            }
                        }
                    }
                }

                $contract->markets()->sync($syncMarkets);
                $contract->roomTypes()->sync($roomTypes);
                $contract->boardTypes()->sync($boardTypes);
                $contract->paxTypes()->sync($paxTypes);
                $contract->measures()->sync($measures);
                $contract->save();

                if (count($unusedRooms) > 0) {
                    foreach ($contract->priceRates as $priceRate) {
                        foreach ($priceRate->settings as $setting) {
                            $contractSettings = json_decode($setting->settings, true);
                            foreach ($unusedRooms as $unusedRoom) {
                                if (array_key_exists($unusedRoom->id, $contractSettings)) {
                                    unset($contractSettings[$unusedRoom->id]);
                                    if (empty($contractSettings)) {
                                        $setting->delete();
                                    }
                                    else {
                                        $setting->settings = json_encode($contractSettings);
                                        $setting->save();
                                    }
                                }
                            }
                        }
                    }
                }

                if ($contract->active != 1) {
                    $clientContracts = $contract->clientContracts;
                    foreach ($clientContracts as $c) {
                        $c->active = false;
                        $c->save();
                    }
                }

                DB::commit();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract updated successfully.';
                $this->response['data'] = $contract;
            }
            catch (\Exception $e) {
                DB::rollBack();
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
        $contract = HotelContract::find($id);

        try {
            $contract->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Contract ' . $contract->name . ' deleted successfully.';
            $this->response['data'] = $contract;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the contract is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function settings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Contract',
            1 => 'Provider',
            2 => 'Hotel'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuContractProvider'] = 'selected';
        $data['submenuContractProviderHotel'] = 'selected';
        $data['breadcrumb'] = $breadcrumb;
        $data['contract_id'] = null;
        $data['currentDate'] = parent::currentDate();

        $id = Input::get('id');
        if ($id != '') {
            $data['contract_id'] = $id;
        }
        return view('contract.provider.hotel.setting')->with($data);
    }

    public function getContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('contractId');
        $query = HotelContract::with([
            'hotel',
            'hotel.hotelChain',
            'roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'markets' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'measures' => function($query) {
                $query->orderBy('id', 'asc');
            },
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
        $contracts = HotelContract::with([
            'hotel',
            'hotel.hotelChain',
            'roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'markets' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'measures' => function($query) {
                $query->orderBy('id', 'asc');
            }
        ])
        ->where('name', 'like', $string)
        ->orderBy('name', 'asc')
        ->get();
        echo json_encode($contracts);
    }

    public function getActivesByName(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $string = '%' . Input::get('q') . '%';
        $contracts = HotelContract::with([
            'hotel',
            'hotel.country',
            'hotel.state',
            'hotel.city',
            'hotel.hotelChain',
            'priceRates',
            'priceRates.market',
            'paxTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'boardTypes' => function($query) {
                $query->orderBy('name', 'asc');
            },
            'roomTypes' => function($query) {
                $query->orderBy('name', 'asc');
            }
        ])
            ->where('name', 'like', $string)
            ->where('active', '1')
            ->orderBy('name', 'asc')
            ->get();
        echo json_encode($contracts);
    }

    public function calculatePrice($cost, $rate) {
        $price = 0;
        if ($rate->type == '1') {
            //$price = $rate->value * $cost / 100 + $cost;
            $price = $cost / (1 - $rate->value / 100);
        }
        else if ($rate->type == '2') {
            $price = $cost + $rate->value;
        }
        if ($rate->round == '1') {
            $price = round($price, 2);
        } else if ($rate->round == '2') {
            $price = round($price);
        } else if ($rate->round == '3') {
            $price = ceil($price);
        }
        return $price;
    }

    public function import(Request $request)    {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        //$importType = Input::get('import-type');
        $priceRate = Input::get('price-rate');
        $contractId = Input::get('contract-id');
        $marketId = Input::get('market-id');
        $roomTypeId = Input::get('room-type-id');

        $importType = '2';

        if ($importType == '1') {
            $fromPriceRate = HotelContractMarket::with([
                'settings'
            ])
                ->where('market_id', $priceRate)
                ->where('hotel_contract_id', $contractId)
                ->first();

            $toPriceRate = HotelContractMarket::with([
                'settings'
            ])
                ->where('market_id', $marketId)
                ->where('hotel_contract_id', $contractId)
                ->first();

            $fromContractSetting = $fromPriceRate['settings'];
            $toContractSetting = $toPriceRate['settings'];

            foreach ($toContractSetting as $contractSetting) {
                $settings = json_decode($contractSetting->settings, true);
                if (array_key_exists($roomTypeId, $settings)) {
                    unset($settings[$roomTypeId]);
                    if (empty($settings)) {
                        $contractSetting->delete();
                    }
                    else {
                        $contractSetting->settings = json_encode($settings);
                        $contractSetting->save();
                    }
                }
            }
            foreach ($fromContractSetting as $from) {
                $founded = false;
                $temp = null;
                foreach ($toContractSetting as $to) {
                    if ($from->date == $to->date) {
                        $founded = true;
                        $temp = $to;
                        break;
                    }
                }
                if ($founded) {
                    $contractSetting = $temp;
                    $fromSettings = json_decode($from->settings, true);
                    $toSettings = json_decode($to->settings, true);
                    $toSettings[$roomTypeId] = $fromSettings[$roomTypeId];
                    $toSettings[$roomTypeId]['2'] = $this->calculatePrice($toSettings[$roomTypeId]['1'], $toPriceRate);
                    $contractSetting->settings = json_encode($toSettings);
                    $contractSetting->save();
                }
                else {
                    $contractSetting = new HotelContractSetting();
                    $contractSetting->hotel_contract_market_id = $toPriceRate->id;
                    $contractSetting->date = $from->date;
                    $fromSettings = json_decode($from->settings, true);
                    $newSettings = array();
                    $newSettings[$roomTypeId] = $fromSettings[$roomTypeId];
                    $newSettings[$roomTypeId]['2'] = $this->calculatePrice($newSettings[$roomTypeId]['1'], $toPriceRate);
                    $contractSetting->settings = json_encode($newSettings);
                    $contractSetting->save();
                }
            }
        }
        else {
            try {
                $fromPriceRate = HotelContractMarket::with([
                    'settings'
                ])
                    ->where('market_id', $priceRate)
                    ->where('hotel_contract_id', $contractId)
                    ->first();

                $toPriceRate = HotelContractMarket::where('market_id', $marketId)
                    ->where('hotel_contract_id', $contractId)
                    ->first();

                HotelContractSetting::where('hotel_contract_market_id', $toPriceRate->id)->delete();

                foreach ($fromPriceRate['settings'] as $fromContractSetting) {
                    $contractSetting = new HotelContractSetting();
                    $contractSetting->hotel_contract_market_id = $toPriceRate->id;
                    $contractSetting->date = $fromContractSetting['date'];
                    $newSettings = json_decode($fromContractSetting['settings'], true);

                    foreach ($newSettings as $key => $value) {
                        $newSettings[$key]['2'] = $this->calculatePrice($value['1'], $toPriceRate);
                    }
                    $contractSetting->settings = json_encode($newSettings);
                    $contractSetting->save();
                }
                $this->response['status'] = 'success';
                $this->response['message'] = 'Petition executed successfully.';
            }
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error, probably the email or username already exist.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        echo json_encode($this->response);
    }

    public function saveSettings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $contractId = Input::get('contract-id');
        $marketId = Input::get('market-id');
        $setPrice = Input::get('set-price');
        $setCost = Input::get('set-cost');
        //$roomTypeId = Input::get('room-type-id');
        $unsetCost = Input::get('unset-cost');
        $roomTypes = Input::get('room-types');
        $roomTypes = json_decode($roomTypes);
        $ranges = Input::get('ranges');
        $ranges = json_decode($ranges);

        if($unsetCost != '') {
            $marketRate = HotelContractMarket::where('market_id', $marketId)->where('hotel_contract_id', $contractId)->first();

            foreach($ranges as $range) {
                $start = Carbon::createFromFormat('d.m.Y', $range->from);
                $end = Carbon::createFromFormat('d.m.Y', $range->to);

                for ($m = $start; $m->lessThanOrEqualTo($end); $m->addDay()) {
                    $contractSetting = HotelContractSetting::where('hotel_contract_market_id', $marketRate->id)->where('date', $m->format('Y-m-d'))->first();
                    if ($contractSetting != null) {
                        $settings = json_decode($contractSetting->settings, true);
                        foreach ($roomTypes as $item) {
                            if (array_key_exists($item, $settings)) {
                                unset($settings[$item]);
                                if (empty($settings)) {
                                    $contractSetting->delete();
                                }
                                else {
                                    $contractSetting->settings = json_encode($settings);
                                    $contractSetting->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        else {
            $marketRate = HotelContractMarket::where('market_id', $marketId)->where('hotel_contract_id', $contractId)->first();
            foreach($ranges as $range) {
                $start = Carbon::createFromFormat('d.m.Y', $range->from);
                $end = Carbon::createFromFormat('d.m.Y', $range->to);

                for ($m = $start; $m->lessThanOrEqualTo($end); $m->addDay()) {
                    $contractSetting = HotelContractSetting::where('hotel_contract_market_id', $marketRate->id)->where('date', $m->format('Y-m-d'))->first();
                    if ($contractSetting === null) {
                        $contractSetting = new HotelContractSetting();
                        $contractSetting->hotel_contract_market_id = $marketRate->id;
                        $contractSetting->date = $m->format('Y-m-d');
                        $settings = array();
                        foreach ($roomTypes as $item) {
                            if($setCost != '' && Input::get('cost') != '') {
                                $settings[$item][$setCost] = Input::get('cost');
                            }
                            if($setPrice != '' && Input::get('price') != '') {
                                $settings[$item][$setPrice] = Input::get('price');
                            }
                            else {
                                $settings[$item]['2'] = $this->calculatePrice(Input::get('cost'), $marketRate);
                            }
                        }
                        $contractSetting->settings = json_encode($settings);
                        $contractSetting->save();
                    }
                    else {
                        $settings = json_decode($contractSetting->settings, true);
                        foreach ($roomTypes as $item) {
                            if($setCost != '') {
                                $settings[$item][$setCost] = Input::get('cost');
                            }
                            if($setPrice != '' && Input::get('price') != '') {
                                $settings[$item][$setPrice] = Input::get('price');
                            }
                            else {
                                $settings[$item]['2'] = $this->calculatePrice(Input::get('cost'), $marketRate);
                            }
                        }
                        $contractSetting->settings = json_encode($settings);
                        $contractSetting->save();
                    }
                }
            }
        }

        $this->response['status'] = 'success';
        $this->response['message'] = 'Petition executed successfully.';
        //$this->response['data'] = $contractSetting;

        echo json_encode($this->response);
    }

    public function settingsByContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $from = Input::get('from');
        $to = Input::get('to');
        $market = Input::get('market');
        $roomTypes = json_decode(Input::get('rooms'));
        $measures = json_decode(Input::get('rows'));
        $start = Carbon::createFromFormat('d.m.Y', $from)->startOfMonth();
        $end = Carbon::createFromFormat('d.m.Y', $to)->endOfMonth();

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
            'priceRates' => function($query) use ($market) {
                $query->where('market_id', $market);
            },
            'priceRates.settings' => function($query) use ($start, $end){
                $query
                    ->orderBy('date', 'asc')
                    ->where('date', '>=', $start->format('Y-m-d'))
                    ->where('date', '<=', $end->format('Y-m-d'));
            },
            'markets' => function($query) use ($market) {
                $query->where('market_id', $market);
            }
        ])->where('id', $id)->first();

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
                        '<!--i class="fa fa-calendar"></i-->' . $m->format("F Y") . ' - ' . $contract->markets[0]->name . '</div>' .
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
            'headerRange' => 'A4:K4',
            'headerText' => 'Hotel Contracts',
            'cellRange' => 'A6:K6'
        );

        $parameters = array(
            'name'=> Input::get('name'),
            'hotel'=> Input::get('hotel'),
            'validFrom'=> Input::get('validFrom'),
            'validTo'=> Input::get('validTo'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new HotelContractExport($parameters), 'Hotel Contracts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
