<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Exports\HotelContractExport;
use App\Models\HotelBoardType;
use App\Models\HotelContract;
use App\Models\HotelContractMarket;
use App\Models\HotelContractPrice;
use App\Models\HotelContractRoomType;
use App\Models\HotelContractSetting;
use App\Models\HotelMeasure;
use App\Models\HotelOfferType;
use App\Models\HotelPaxType;
use App\Models\HotelRoomType;
use App\Models\Location;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
        $measures = HotelMeasure::where('active', '1')->orderBy('id', 'asc')->get();
        //$measures = HotelMeasure::where('active', '1')->whereIn('code', ['cost', 'price'])->orderBy('id', 'asc')->get();
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
        $columns = array('hotel_contracts.id', 'hotel_contracts.name', 'hotels.name', 'hotel.location', 'hotel_contracts.valid_from', 'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.active', 'hotel_contracts.hotel_id');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchHotel = Input::get('columns')['2']['search']['value'];
        $searchLocation = Input::get('columns')['3']['search']['value'];
        $searchValidFrom = Input::get('columns')['4']['search']['value'];
        $searchValidTo = Input::get('columns')['5']['search']['value'];
        $searchActive = Input::get('columns')['7']['search']['value'];
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
        if(isset($searchLocation) && $searchLocation != '') {
            $locations = Location::where('name', 'like', '%' . $searchLocation . '%')->get();
            $allLocationIds = array();
            foreach ($locations as $location) {
                $allLocations = Location::descendantsAndSelf($location->id);
                foreach ($allLocations as $aux) {
                    $allLocationIds[] = $aux->id;
                }
            }
            $query->whereHas('hotel', function ($query) use ($allLocationIds) {
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

            $location = 'Undefined';
            if (isset($r->hotel->city->name) && !is_null($r->hotel->city->name))
                $location = $r->hotel->city->name;
            else if (isset($r->hotel->state->name) && !is_null($r->hotel->state->name))
                $location = $r->hotel->state->name;
            else if (isset($r->hotel->country->name) && !is_null($r->hotel->country->name))
                $location = $r->hotel->country->name;

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'hotel' => $r->hotel->name,
                'valid_from' => $validFrom->format('d.m.Y'),
                'valid_to' => $validTo->format('d.m.Y'),
                'location' => $location ,
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
            $contract = new HotelContract();
            $contract->name = Input::get('name');
            $contract->hotel_id = Input::get('hotel-id');
            $contract->valid_from = Carbon::createFromFormat('!d.m.Y', Input::get('valid-from'))->format('Y-m-d');
            $contract->valid_to = Carbon::createFromFormat('!d.m.Y', Input::get('valid-to'))->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? 1 : 0;
            $contract->status = Input::get('status');

            DB::beginTransaction();
            try {
                $contract->save();
                $roomTypes = json_decode(Input::get('roomTypes'), true);
                $boardTypes = json_decode(Input::get('boardTypes'));
                $paxTypes = json_decode(Input::get('paxTypes'));
                $markets = json_decode(Input::get('markets'));
                $measures = json_decode(Input::get('measures'));

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
            $contract = HotelContract::find($id);

            if (!is_null($contract)) {
                $validFrom = Carbon::createFromFormat('d.m.Y', Input::get('valid-from'));
                $validTo = Carbon::createFromFormat('d.m.Y', Input::get('valid-to'));
                $contract->name = Input::get('name');
                $contract->hotel_id = Input::get('hotel-id');
                $contract->valid_from = $validFrom->format('Y-m-d');
                $contract->valid_to = $validTo->format('Y-m-d');
                $contract->active = Input::get('active') == 1 ? 1 : 0;
                $contract->status = Input::get('status');

                DB::beginTransaction();
                try {
                    $roomTypes = json_decode(Input::get('roomTypes'), true);
                    $boardTypes = json_decode(Input::get('boardTypes'));
                    $markets = json_decode(Input::get('markets'));
                    $paxTypes = json_decode(Input::get('paxTypes'));
                    $measures = json_decode(Input::get('measures'));

                    $syncMarkets = array();
                    foreach ($markets as $k) {
                        $syncMarkets[$k->market_id] = array(
                            'type' => $k->rate_type,
                            'value' => $k->value,
                            'round' => $k->round_type
                        );
                        $priceRate = new HotelContractMarket();
                        $priceRate->type = $k->rate_type;
                        $priceRate->value = $k->value;
                        $priceRate->round = $k->round_type;
                        if ($k->update_price == '1') {
                            $query = HotelContractPrice::where('market_id', $k->market_id)
                                ->whereHas('setting', function ($query) use ($contract) {
                                    $query->where('hotel_contract_id', $contract->id);
                                });
                            $prices = $query->get();
                            foreach ($prices as $price) {
                                $price->price_adult = $this->calculatePrice($price->cost_adult, $priceRate);
                                $price->save();
                            }
                        }
                    }

                    $contract->markets()->sync($syncMarkets);
                    $contract->roomTypes()->sync($roomTypes);
                    $contract->boardTypes()->sync($boardTypes);
                    $contract->paxTypes()->sync($paxTypes);
                    $contract->measures()->sync($measures);
                    $contract->save();

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
            else {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Contract invalid.';
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

    public function duplicate(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $contract = HotelContract::with([
            'paxTypes',
            'boardTypes',
            'roomTypes',
            'measures',
            'priceRates',
            'roomTypeRelations',
            'settings',
            'settings.prices'
        ])->find($id);

        if (!is_null($contract)) {
            $newContract = new HotelContract();
            $newContract->name = $contract->name . ' DUPLICATED!';
            $newContract->hotel_id = $contract->hotel_id;
            $newContract->valid_from = $contract->valid_from;
            $newContract->valid_to = $contract->valid_to;
            $newContract->active = $contract->active;
            $newContract->status = $contract->status;

            DB::beginTransaction();
            try {
                $newContract->save();

                foreach ($contract->paxTypes as $item) {
                    $newContract->paxTypes()->attach($item->id);
                }
                foreach ($contract->boardTypes as $item) {
                    $newContract->boardTypes()->attach($item->id);
                }
                foreach ($contract->measures as $item) {
                    $newContract->measures()->attach($item->id);
                }
                $contractRoomTypes = array();
                foreach ($contract->roomTypeRelations as $item) {
                    $contractRoomType = new HotelContractRoomType();
                    $contractRoomType->hotel_contract_id = $newContract->id;
                    $contractRoomType->hotel_room_type_id = $item->hotel_room_type_id;
                    $contractRoomType->save();
                    $contractRoomTypes[$item->id] = $contractRoomType->id;
                }
                $priceRates = array();
                foreach ($contract->priceRates as $item) {
                    $priceRate = new HotelContractMarket();
                    $priceRate->hotel_contract_id = $newContract->id;
                    $priceRate->market_id = $item->market_id;
                    $priceRate->type = $item->type;
                    $priceRate->value = $item->value;
                    $priceRate->round = $item->round;
                    $priceRate->save();
                    $priceRates[$item->id] = $priceRate->id;
                }
                foreach ($contract->settings as $setting) {
                    $newSetting = new HotelContractSetting();
                    $newSetting->hotel_contract_id = $newContract->id;
                    $newSetting->hotel_contract_room_type_id = $contractRoomTypes[$setting->hotel_contract_room_type_id];
                    $newSetting->hotel_room_type_id = $setting->hotel_room_type_id;
                    $newSetting->date = $setting->date;
                    $newSetting->allotment_base = $setting->allotment_base;
                    $newSetting->allotment = $setting->allotment;
                    $newSetting->release = $setting->release;
                    $newSetting->stop_sale = $setting->stop_sale;
                    $newSetting->save();
                    foreach ($setting->prices as $price) {
                        $newPrice = new HotelContractPrice();
                        $newPrice->hotel_contract_setting_id = $newSetting->id;
                        $newPrice->price_rate_id = $priceRates[$price->price_rate_id];
                        $newPrice->market_id = $price->market_id;
                        $newPrice->cost_adult = $price->cost_adult;
                        $newPrice->price_adult = $price->price_adult;
                        $newPrice->cost_children_1 = $price->cost_children_1;
                        $newPrice->price_children_1 = $price->price_children_1;
                        $newPrice->cost_children_2 = $price->cost_children_2;
                        $newPrice->price_children_2 = $price->price_children_2;
                        $newPrice->cost_children_3 = $price->cost_children_3;
                        $newPrice->price_children_3 = $price->price_children_3;
                        $newPrice->save();
                    }
                }
                DB::commit();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract ' . $contract->name . ' duplicated successfully.';
            }
            catch (\Exception $e) {
                DB::rollBack();
                $this->response['status'] = 'error';
                $this->response['message'] = 'Something was wrong, please contact the system administrator.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        else {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid contract.';
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

        $offerTypes = HotelOfferType::where('active', '1')->get();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuContract'] = 'selected';
        $data['submenuContractProvider'] = 'selected';
        $data['submenuContractProviderHotel'] = 'selected';
        $data['breadcrumb'] = $breadcrumb;
        $data['contract_id'] = null;
        $data['currentDate'] = parent::currentDate();
        $data['offerTypes'] = $offerTypes;

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
            if (is_null($contract)) {
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

    public function importCostFromPriceRate(Request $request)    {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $priceRateFrom = Input::get('price-rate-from');
        $contractId = Input::get('contract-id');
        $priceRateTo = Input::get('price-rate-to');

        try {
            $toPriceRate = HotelContractMarket::where('market_id', $priceRateTo)
                ->where('hotel_contract_id', $contractId)
                ->first();

            $query = HotelContractPrice::where('market_id', $priceRateFrom)
                ->whereHas('setting', function ($query) use ($contractId) {
                    $query->where('hotel_contract_id', $contractId);
                });
            $prices = $query->get();

            if (count($prices) > 0) {
                HotelContractPrice::where('price_rate_id', $toPriceRate->id)->delete();
                foreach ($prices as $price) {
                    $newPrice = new HotelContractPrice();
                    $newPrice->hotel_contract_setting_id = $price->hotel_contract_setting_id;
                    $newPrice->price_rate_id = $toPriceRate->id;
                    $newPrice->market_id = $toPriceRate->market_id;
                    $newPrice->cost_adult = $price->cost_adult;
                    $newPrice->price_adult = !is_null($price->cost_adult) ? $this->calculatePrice($price->cost_adult, $toPriceRate) : null;
                    $newPrice->cost_children_1 = $price->cost_children_1;
                    $newPrice->price_children_1 = !is_null($price->cost_children_1) ? $this->calculatePrice($price->cost_children_1, $toPriceRate) : null;
                    $newPrice->cost_children_2 = $price->cost_children_2;
                    $newPrice->price_children_2 = !is_null($price->cost_children_2) ? $this->calculatePrice($price->cost_children_2, $toPriceRate) : null;
                    $newPrice->cost_children_3 = $price->cost_children_3;
                    $newPrice->price_children_3 = !is_null($price->cost_children_3) ? $this->calculatePrice($price->cost_children_3, $toPriceRate) : null;
                    if (!is_null($price->cost_children_1_use_adult_type)) {
                        $newPrice->cost_children_1_use_adult_type = $price->cost_children_1_use_adult_type;
                        $newPrice->cost_children_1_use_adult_rate = $price->cost_children_1_use_adult_rate;
                    }
                    if (!is_null($price->cost_children_2_use_adult_type)) {
                        $newPrice->cost_children_2_use_adult_type = $price->cost_children_2_use_adult_type;
                        $newPrice->cost_children_2_use_adult_rate = $price->cost_children_2_use_adult_rate;
                    }
                    if (!is_null($price->cost_children_3_use_adult_type)) {
                        $newPrice->cost_children_3_use_adult_type = $price->cost_children_3_use_adult_type;
                        $newPrice->cost_children_3_use_adult_rate = $price->cost_children_3_use_adult_rate;
                    }
                    $newPrice->save();
                }
                $this->response['status'] = 'success';
                $this->response['message'] = 'Petition executed successfully.';
            }
            else {
                $this->response['status'] = 'warning';
                $this->response['message'] = 'There is not prices to import.';
            }
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }

        echo json_encode($this->response);
    }

    public function calcBonus($rate, $cost, $type) {
        $bonus = 0;
        if ($type == 1) {
            $bonus = $rate * $cost / 100;
        }
        else if ($type == 2) {
            $bonus = $rate;
        }
        return $bonus;
    }

    public function importCostFromRoomType(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $fromRoom = Input::get('select-room');
        $contractId = Input::get('contract-id');
        $marketId = Input::get('market-id');
        $toRoom = Input::get('room-type-id');
        $addValue = Input::get('add-value');
        $rateType = Input::get('rate_type');
        $ratePercentValue = Input::get('rate_percent_value');
        $rateFeeValue = Input::get('rate_fee_value');
        $ranges = Input::get('ranges');
        $ranges = json_decode($ranges);
        $contract = null;
        $rateBonus = $ratePercentValue != '' ? $ratePercentValue : $rateFeeValue;

        try {
            $priceRate = HotelContractMarket::where('market_id', $marketId)->where('hotel_contract_id', $contractId)->first();
            $fromRoomType = HotelRoomType::where('id', $fromRoom)->first();
            $toRoomType = HotelRoomType::where('id', $toRoom)->first();

            if (is_null($fromRoomType) || is_null($toRoomType) || is_null($priceRate)) {
                $this->response['status'] = 'warning';
                $this->response['message'] = 'Room Type or Price Rate not found.';
            }
            else {
                foreach($ranges as $range) {
                    $start = Carbon::createFromFormat('d.m.Y', $range->from);
                    $end = Carbon::createFromFormat('d.m.Y', $range->to);

                    if ($fromRoom == $toRoom) {
                        if ($addValue != '') {
                            $query = HotelContractPrice::where('price_rate_id', $priceRate->id)
                                ->whereHas('setting', function ($query) use ($contractId, $fromRoom, $start, $end) {
                                    $query
                                        ->where('hotel_contract_id', $contractId)
                                        ->where('hotel_room_type_id', $fromRoom)
                                        ->where('date', '>=', $start->format('Y-m-d'))
                                        ->where('date', '<=', $end->format('Y-m-d'));
                                });
                            $prices = $query->get();

                            foreach ($prices as $price) {
                                if (!is_null($price->cost_adult)) {
                                    $price->cost_adult = $price->cost_adult + $this->calcBonus($rateBonus, $price->cost_adult, $rateType);
                                    $price->price_adult = $this->calculatePrice($price->cost_adult, $priceRate);
                                }
                                if (!is_null($price->cost_children_1)) {
                                    if (!is_null($price->cost_children_1_use_adult_type)) {
                                        $price->cost_children_1 = $this->getCostFromAdult($price->cost_adult, $price->cost_children_1_use_adult_type, $price->cost_children_1_use_adult_rate);
                                        $price->price_children_1 = $this->calculatePrice($price->cost_children_1, $priceRate);
                                    }
                                    else {
                                        $price->cost_children_1 = null;
                                        $price->price_children_1 = null;
                                    }
                                }
                                if (!is_null($price->cost_children_2)) {
                                    if (!is_null($price->cost_children_2_use_adult_type)) {
                                        $price->cost_children_2 = $this->getCostFromAdult($price->cost_adult, $price->cost_children_2_use_adult_type, $price->cost_children_2_use_adult_rate);
                                        $price->price_children_2 = $this->calculatePrice($price->cost_children_2, $priceRate);
                                    }
                                    else {
                                        $price->cost_children_2 = null;
                                        $price->price_children_2 = null;
                                    }
                                }
                                if (!is_null($price->cost_children_3)) {
                                    if (!is_null($price->cost_children_3_use_adult_type)) {
                                        $price->cost_children_3 = $this->getCostFromAdult($price->cost_adult, $price->cost_children_3_use_adult_type, $price->cost_children_3_use_adult_rate);
                                        $price->price_children_3 = $this->calculatePrice($price->cost_children_3, $priceRate);
                                    }
                                    else {
                                        $price->cost_children_3 = null;
                                        $price->price_children_3 = null;
                                    }
                                }
                                $price->save();
                            }
                        }
                    }
                    else {
                        $query = HotelContractPrice::with('setting')->where('price_rate_id', $priceRate->id)
                            ->whereHas('setting', function ($query) use ($contractId, $fromRoom, $start, $end) {
                                $query
                                    ->where('hotel_contract_id', $contractId)
                                    ->where('hotel_room_type_id', $fromRoom)
                                    ->where('date', '>=', $start->format('Y-m-d'))
                                    ->where('date', '<=', $end->format('Y-m-d'));
                            });
                        $prices = $query->get();

                        foreach ($prices as $price) {
                            $setting = HotelContractSetting::where('date', $price->setting->date)
                                ->where('hotel_room_type_id', $toRoom)
                                ->where('hotel_contract_id', $contractId)
                                ->first();
                            $contractPrice = null;
                            if(is_null($setting)) {
                                $setting = new HotelContractSetting();
                                $contractRoomType = HotelContractRoomType::where('hotel_contract_id', $contractId)
                                    ->where('hotel_room_type_id', $toRoom)->first();
                                $setting->hotel_contract_id = $contractId;
                                $setting->hotel_contract_room_type_id = $contractRoomType->id;
                                $setting->hotel_room_type_id = $toRoom;
                                $setting->date = $price->setting->date;
                                $setting->save();
                                $contractPrice = new HotelContractPrice();
                                $contractPrice->hotel_contract_setting_id = $setting->id;
                                $contractPrice->price_rate_id = $priceRate->id;
                                $contractPrice->market_id = $price->market_id;
                            }
                            else {
                                $contractPrice = HotelContractPrice::where('hotel_contract_setting_id', $setting->id)
                                    ->where('price_rate_id', $priceRate->id)->first();
                                if (is_null($contractPrice)) {
                                    $contractPrice = new HotelContractPrice();
                                    $contractPrice->hotel_contract_setting_id = $setting->id;
                                    $contractPrice->price_rate_id = $priceRate->id;
                                    $contractPrice->market_id = $price->market_id;
                                }
                            }
                            if (!is_null($price->cost_adult)) {
                                $contractPrice->cost_adult = $price->cost_adult + $this->calcBonus($rateBonus, $price->cost_adult, $rateType);
                                $contractPrice->price_adult = $this->calculatePrice($contractPrice->cost_adult, $priceRate);
                            }
                            if ($toRoomType->max_children >= 1 && $fromRoomType->max_children >= 1 && !is_null($price->cost_children_1) && !is_null($price->cost_children_1_use_adult_type)) {
                                $contractPrice->cost_children_1_use_adult_rate = $price->cost_children_1_use_adult_rate;
                                $contractPrice->cost_children_1_use_adult_type = $price->cost_children_1_use_adult_type;
                                $contractPrice->cost_children_1 = $this->getCostFromAdult($contractPrice->cost_adult, $contractPrice->cost_children_1_use_adult_type, $contractPrice->cost_children_1_use_adult_rate);
                                $contractPrice->price_children_1 = $this->calculatePrice($contractPrice->cost_children_1, $priceRate);
                            }
                            else {
                                $contractPrice->cost_children_1_use_adult_rate = null;
                                $contractPrice->cost_children_1_use_adult_type = null;
                                $contractPrice->cost_children_1 = null;
                                $contractPrice->price_children_1 = null;
                            }
                            if ($toRoomType->max_children > 1 && $fromRoomType->max_children <= 2 && !is_null($price->cost_children_2) && !is_null($price->cost_children_2_use_adult_type)) {
                                $contractPrice->cost_children_2_use_adult_rate = $price->cost_children_2_use_adult_rate;
                                $contractPrice->cost_children_2_use_adult_type = $price->cost_children_2_use_adult_type;
                                $contractPrice->cost_children_2 = $this->getCostFromAdult($contractPrice->cost_adult, $contractPrice->cost_children_2_use_adult_type, $contractPrice->cost_children_2_use_adult_rate);
                                $contractPrice->price_children_2 = $this->calculatePrice($contractPrice->cost_children_2, $priceRate);
                            }
                            else {
                                $contractPrice->cost_children_2_use_adult_rate = null;
                                $contractPrice->cost_children_2_use_adult_type = null;
                                $contractPrice->cost_children_2 = null;
                                $contractPrice->price_children_2 = null;
                            }
                            if ($toRoomType->max_children > 2 && $fromRoomType->max_children <= 3 && !is_null($price->cost_children_3) && !is_null($price->cost_children_3_use_adult_type)) {
                                $contractPrice->cost_children_3_use_adult_rate = $price->cost_children_3_use_adult_rate;
                                $contractPrice->cost_children_3_use_adult_type = $price->cost_children_3_use_adult_type;
                                $contractPrice->cost_children_3 = $this->getCostFromAdult($contractPrice->cost_adult, $contractPrice->cost_children_3_use_adult_type, $contractPrice->cost_children_3_use_adult_rate);
                                $contractPrice->price_children_3 = $this->calculatePrice($contractPrice->cost_children_3, $priceRate);
                            }
                            else {
                                $contractPrice->cost_children_3_use_adult_rate = null;
                                $contractPrice->cost_children_3_use_adult_type = null;
                                $contractPrice->cost_children_3 = null;
                                $contractPrice->price_children_3 = null;
                            }
                            $contractPrice->save();
                        }
                    }
                }
            }
            $this->response['status'] = 'success';
            $this->response['message'] = 'Petition executed successfully.';
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function getCostFromAdult($adultCost, $type, $rate) {
        $cost = 0;
        if ($type == 1) {
            $bonus = $rate * $adultCost / 100;
            $cost = $adultCost + $bonus;
        }
        else if ($type == 2) {
            $cost = $adultCost + $rate;
        }
        return $cost;
    }

    public function saveSettings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $contractId = Input::get('contract-id');
        $marketId = Input::get('market-id');
        $setPrice = Input::get('set-price');
        $setCost = Input::get('set-cost');
        $setAllotment = Input::get('set-allotment');
        $setRelease = Input::get('set-release');
        $setStopSale = Input::get('set-stop_sale');
        $unsetStopSale = Input::get('unset-stop_sale');
        $unsetAllotment = Input::get('unset-allotment');
        $unsetRelease = Input::get('unset-release');
        $unsetCost = Input::get('unset-cost');
        $roomTypes = Input::get('room-types');
        $roomTypes = json_decode($roomTypes);
        $ranges = Input::get('ranges');
        $ranges = json_decode($ranges);

        DB::beginTransaction();
        try {
            $marketRate = HotelContractMarket::where('market_id', $marketId)->where('hotel_contract_id', $contractId)->first();
            foreach($ranges as $range) {
                $start = Carbon::createFromFormat('d.m.Y', $range->from);
                $end = Carbon::createFromFormat('d.m.Y', $range->to);
                $now = Carbon::createFromFormat('d.m.Y', $this->currentDate());
                /*if ($start->lessThan($now)) {
                    throw new CustomException('Can not update dates less than ' . $now->format('d.m.Y') . '.');
                }*/
                for ($m = $start; $m->lessThanOrEqualTo($end); $m->addDay()) {
                    foreach ($roomTypes as $roomTypeId) {
                        $contractSetting = HotelContractSetting::with('prices', 'roomType')
                            ->where('hotel_contract_id', $contractId)
                            ->where('date', $m->format('Y-m-d'))
                            ->where('hotel_room_type_id', $roomTypeId)
                            ->first();
                        if (isset($unsetCost) && isset($unsetAllotment) && isset($unsetRelease) && (isset($unsetStopSale) || (!is_null($contractSetting) && $contractSetting->stop_sale == 0))) {
                            $contractSetting->delete();
                        }
                        else {
                            $contractRoomType = HotelContractRoomType::where('hotel_room_type_id', $roomTypeId)->where('hotel_contract_id', $contractId)->first();
                            if (is_null($contractSetting)) {
                                $contractSetting = new HotelContractSetting();
                                $contractSetting->hotel_contract_id = $contractId;
                                $contractSetting->hotel_contract_room_type_id = $contractRoomType->id;
                                $contractSetting->hotel_room_type_id = $roomTypeId;
                                $contractSetting->date = $m->format('Y-m-d');
                            }
                            if (isset($unsetAllotment)) {
                                if (isset($contractSetting->allotment_sold) && $contractSetting->allotment_sold > 0)
                                    throw new CustomException('There are allotments sold in the selected range.');
                                $contractSetting->allotment_base = null;
                                $contractSetting->allotment_sold = null;
                                $contractSetting->allotment = null;
                            }
                            else if($setAllotment != '') {
                                $allotment = Input::get('allotment');
                                if (is_null($contractSetting->allotment_base)) {
                                    $contractSetting->allotment_base = $allotment;
                                    $contractSetting->allotment_sold = 0;
                                    $contractSetting->allotment = $allotment;
                                }
                                else {
                                    if ($contractSetting->allotment_sold == 0) {
                                        $contractSetting->allotment_base = $allotment;
                                        $contractSetting->allotment = $allotment;
                                        $contractSetting->allotment_sold = 0;
                                    }
                                    else {
                                        if ($allotment <= $contractSetting->allotment_sold) {
                                            $contractSetting->allotment_base = $contractSetting->allotment_sold;
                                            $contractSetting->allotment = 0;
                                        }
                                        else {
                                            $rate = $allotment - $contractSetting->allotment_base;
                                            $contractSetting->allotment_base = $allotment;
                                            $contractSetting->allotment = $contractSetting->allotment + $rate;
                                        }
                                    }
                                }
                            }
                            if (isset($unsetRelease)) {
                                $contractSetting->release = null;
                            }
                            else if($setRelease != '') {
                                $contractSetting->release = Input::get('release');
                            }
                            if (isset($unsetStopSale)) {
                                $contractSetting->stop_sale = null;
                            }
                            else if($setStopSale != '') {
                                $temp = Input::get('stop_sale');
                                $stopSale = (isset($temp) && $temp != '') ? $temp : 0;
                                $contractSetting->stop_sale = $stopSale;
                                DB::table('hotel_contract_client_settings')
                                    ->where('hotel_contract_setting_id', $contractSetting->id)
                                    ->update(['stop_sale' => $stopSale]);
                            }

                            $contractSetting->save();

                            if (isset($unsetCost)) {
                                foreach($contractSetting->prices as $item) {
                                    if($item->hotel_contract_setting_id == $contractSetting->id && $item->market_id == $marketRate->market_id) {
                                        $item->delete();
                                        break;
                                    }
                                }
                            }
                            else if($setCost != '' || $setPrice != '') {
                                $price = null;
                                foreach($contractSetting->prices as $item) {
                                    if($item->hotel_contract_setting_id == $contractSetting->id && $item->market_id == $marketRate->market_id) {
                                        $price = $item;
                                        break;
                                    }
                                }
                                if (is_null($price)) {
                                    $price = new HotelContractPrice();
                                    $price->market_id = $marketRate->market_id;
                                    $price->price_rate_id = $marketRate->id;
                                }
                                $price->hotel_contract_setting_id = $contractSetting->id;
                                $roomType = $contractSetting->roomType;
                                if($setCost != '') {
                                    $price->cost_adult = Input::get('cost');
                                    if (isset($roomType->max_children) && $roomType->max_children > 0 && $roomType->max_children <= 3) {
                                        if ($roomType->max_children == 1) {
                                            if (Input::get('use-adult-cost_children_1') != '') {
                                                $type = Input::get('use-adult-type-cost_children_1');
                                                $rate = Input::get('use-adult-rate-cost_children_1');
                                                $price->cost_children_1_use_adult_type = $type;
                                                $price->cost_children_1_use_adult_rate = $rate;
                                                $price->cost_children_1 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_1 = Input::get('cost_children_1');
                                                $price->cost_children_1_use_adult_type = null;
                                                $price->cost_children_1_use_adult_rate = null;
                                            }
                                        }
                                        else if ($roomType->max_children == 2) {
                                            if (Input::get('use-adult-cost_children_1') != '') {
                                                $type = Input::get('use-adult-type-cost_children_1');
                                                $rate = Input::get('use-adult-rate-cost_children_1');
                                                $price->cost_children_1_use_adult_type = $type;
                                                $price->cost_children_1_use_adult_rate = $rate;
                                                $price->cost_children_1 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_1 = Input::get('cost_children_1');
                                                $price->cost_children_1_use_adult_type = null;
                                                $price->cost_children_1_use_adult_rate = null;
                                            }
                                            if (Input::get('use-adult-cost_children_2') != '') {
                                                $type = Input::get('use-adult-type-cost_children_2');
                                                $rate = Input::get('use-adult-rate-cost_children_2');
                                                $price->cost_children_2_use_adult_type = $type;
                                                $price->cost_children_2_use_adult_rate = $rate;
                                                $price->cost_children_2 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_2 = Input::get('cost_children_2');
                                                $price->cost_children_2_use_adult_type = null;
                                                $price->cost_children_2_use_adult_rate = null;
                                            }
                                        }
                                        else if ($roomType->max_children == 3) {
                                            if (Input::get('use-adult-cost_children_1') != '') {
                                                $type = Input::get('use-adult-type-cost_children_1');
                                                $rate = Input::get('use-adult-rate-cost_children_1');
                                                $price->cost_children_1_use_adult_type = $type;
                                                $price->cost_children_1_use_adult_rate = $rate;
                                                $price->cost_children_1 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_1 = Input::get('cost_children_1');
                                                $price->cost_children_1_use_adult_type = null;
                                                $price->cost_children_1_use_adult_rate = null;
                                            }
                                            if (Input::get('use-adult-cost_children_2') != '') {
                                                $type = Input::get('use-adult-type-cost_children_2');
                                                $rate = Input::get('use-adult-rate-cost_children_2');
                                                $price->cost_children_2_use_adult_type = $type;
                                                $price->cost_children_2_use_adult_rate = $rate;
                                                $price->cost_children_2 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_2 = Input::get('cost_children_2');
                                                $price->cost_children_2_use_adult_type = null;
                                                $price->cost_children_2_use_adult_rate = null;
                                            }
                                            if (Input::get('use-adult-cost_children_3') != '') {
                                                $type = Input::get('use-adult-type-cost_children_3');
                                                $rate = Input::get('use-adult-rate-cost_children_3');
                                                $price->cost_children_3_use_adult_type = $type;
                                                $price->cost_children_3_use_adult_rate = $rate;
                                                $price->cost_children_3 = $this->getCostFromAdult($price->cost_adult, $type, $rate);
                                            }
                                            else {
                                                $price->cost_children_3 = Input::get('cost_children_3');
                                                $price->cost_children_3_use_adult_type = null;
                                                $price->cost_children_3_use_adult_rate = null;
                                            }
                                        }
                                    }
                                }
                                if($setPrice != '' && Input::get('price') != '') {
                                    $price->price_adult = Input::get('price');
                                    if ($roomType->max_children == 1) {
                                        $price->price_children_1 = Input::get('price_children_1');
                                    }
                                    else if ($roomType->max_children == 2) {
                                        $price->price_children_1 = Input::get('price_children_1');
                                        $price->price_children_2 = Input::get('price_children_2');
                                    }
                                    else if ($roomType->max_children == 3) {
                                        $price->price_children_1 = Input::get('price_children_1');
                                        $price->price_children_2 = Input::get('price_children_2');
                                        $price->price_children_3 = Input::get('price_children_3');
                                    }
                                }
                                else {
                                    $price->price_adult = $this->calculatePrice($price->cost_adult, $marketRate);
                                    if ($roomType->max_children == 1) {
                                        $price->price_children_1 = $this->calculatePrice($price->cost_children_1, $marketRate);
                                    }
                                    else if ($roomType->max_children == 2) {
                                        $price->price_children_1 = $this->calculatePrice($price->cost_children_1, $marketRate);
                                        $price->price_children_2 = $this->calculatePrice($price->cost_children_2, $marketRate);
                                    }
                                    else if ($roomType->max_children == 3) {
                                        $price->price_children_1 = $this->calculatePrice($price->cost_children_1, $marketRate);
                                        $price->price_children_2 = $this->calculatePrice($price->cost_children_2, $marketRate);
                                        $price->price_children_3 = $this->calculatePrice($price->cost_children_3, $marketRate);
                                    }
                                }
                                $price->save();
                            }
                        }
                    }
                }
            }
            DB::commit();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Petition executed successfully.';
            //$this->response['data'] = $contractSetting;
        }
        catch (CustomException $e) {
            DB::rollBack();
            $this->response['status'] = 'error';
            $this->response['message'] = $e->getMessage();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
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
            'settings' => function($query) use ($start, $end){
                $query
                    ->orderBy('date', 'asc')
                    ->where('date', '>=', $start->format('Y-m-d'))
                    ->where('date', '<=', $end->format('Y-m-d'));
            },
            'settings.prices' => function($query) use ($market) {
                $query->where('market_id', $market);
            },
            'markets' => function($query) use ($market) {
                $query->where('market_id', $market);
            },
            'offers' => function($query) use ($start, $end) {
                $query->whereHas('ranges', function ($query) use ($start, $end) {
                    $query
                        ->where('to', '>=', $start->format('Y-m-d'))
                        ->where('from', '<=', $end->format('Y-m-d'));
                })->where('active', '1');
            },
            'offers.rooms',
            'offers.rooms.roomType',
            'offers.ranges' => function($query) use ($start, $end) {
                $query
                    ->where('to', '>=', $start->format('Y-m-d'))
                    ->where('from', '<=', $end->format('Y-m-d'));
            }
        ])->where('id', $id)->first();

        $operateMeasures = $contract->measures;

        if (is_null($contract)) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid contract.';
        }
        else {
            $validFrom = Carbon::createFromFormat('!Y-m-d', $contract->valid_from);
            $validTo = Carbon::createFromFormat('!Y-m-d', $contract->valid_to);

            $offerDates = array();
            $offers = $contract->offers;
            foreach ($offers as $offer) {
                foreach ($offer->ranges as $range) {
                    $startRange = Carbon::createFromFormat('!Y-m-d', $range->from);
                    $endRange = Carbon::createFromFormat('!Y-m-d', $range->to);
                    if ($start->greaterThanOrEqualTo($startRange)) {
                        //$startRange = $start;
                        $startRange = Carbon::createFromFormat('!Y-m-d', $start->format('Y-m-d'));
                    }
                    if ($end->lessThanOrEqualTo($endRange)) {
                        //$endRange = $end;
                        $endtRange = Carbon::createFromFormat('!Y-m-d', $end->format('Y-m-d'));
                    }
                    for ($o = $startRange; $o->lessThanOrEqualTo($endRange); $o->addDay()) {
                        foreach ($offer->rooms as $room) {
                            $offerDates[$o->format('Y-m-d')][$room->hotel_room_type_id] = $offer->id;
                        }
                    }
                }
            }

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
                    $object->allotment = $setting->allotment;
                    $object->allotment_sold = $setting->allotment_sold;
                    $object->allotment_base = $setting->allotment_base;
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
                        $m->format("F Y") . ' - ' . $contract->markets[0]->name . '</div>' .
                        '<div class="tools tools-setting">' .
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
                                if ($measure->code == 'cost') {
                                    for ($x = 1; $x <= $roomTypes[$r]->max_children; $x ++) {
                                        $newMeasure = new HotelMeasure();
                                        $newMeasure->id = 1000 + $x;
                                        $newMeasure->name = 'Cost Ch ' . $x;
                                        $newMeasure->active = 1;
                                        $newMeasure->code = 'cost_children_' . $x;
                                        $newMeasure->parent = 'cost-' . $roomTypes[$r]->id;
                                        $rows[] = $newMeasure;
                                    }
                                }
                                else if ($measure->code == 'price') {
                                    for ($x = 1; $x <= $roomTypes[$r]->max_children; $x ++) {
                                        $newMeasure = new HotelMeasure();
                                        $newMeasure->id = 2000 + $x;
                                        $newMeasure->name = 'Price Ch ' . $x;
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
                                $newMeasure = new HotelMeasure();
                                $newMeasure->id = 3002;
                                $newMeasure->name = 'Allot. Base';
                                $newMeasure->active = 1;
                                $newMeasure->code = 'allotment_base';
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
                            if ($rows[$v]->code == 'cost' || $rows[$v]->code == 'price') {
                                $table .=
                                '<tr data-row="' . $rows[$v]->id . '">' .
                                '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . $rows[$v]->name . ' Ad';
                                if ($roomTypes[$r]->max_children > 0) {
                                    $table .= '<button class="measure-detail btn-default closed" data="' . $rows[$v]->code . '-' . $roomTypes[$r]->id .'" data-measure="' . $rows[$v]->code . '">+</button>';
                                }
                                $table .= '</td>';
                            }
                            else if ($rows[$v]->code == 'allotment') {
                                $table .=
                                    '<tr data-row="' . $rows[$v]->id . '">' .
                                    '<td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . $rows[$v]->name;
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
                                '><td class="column-setting item-variable" data-measure-code="' . $rows[$v]->code . '">' . $rows[$v]->name . '</td>';
                            }
                            $month = $m->format('d.m.Y');
                            $monthStart = Carbon::createFromFormat('d.m.Y', $month)->startOfMonth();
                            $monthEnd = Carbon::createFromFormat('d.m.Y', $month)->endOfMonth();

                            for ($i = $monthStart; $i->lessThanOrEqualTo($monthEnd); $i->addDay()) {
                                $value = '';
                                $showValue = '';
                                $useAdultType = '';
                                $useAdultRate = '';
                                $usableClass = 'item-disabled';
                                if ($validFrom->lessThanOrEqualTo($i) && $validTo->greaterThanOrEqualTo($i)) {
                                    $usableClass = 'item-setting';
                                    if (array_key_exists($i->format('Y-m-d'), $settings)) {
                                        $data = $settings[$i->format('Y-m-d')];
                                        if (isset($data[$roomTypes[$r]->id])) {
                                            $object = $data[$roomTypes[$r]->id];
                                            if ($rows[$v]->code == 'cost') { $value = $object->cost_adult; $showValue = $value; }
                                            else if ($rows[$v]->code == 'cost_children_1') { $value = $object->cost_children_1; $showValue = $value; $useAdultType = $object->cost_children_1_use_adult_type; $useAdultRate = $object->cost_children_1_use_adult_rate; }
                                            else if ($rows[$v]->code == 'cost_children_2') { $value = $object->cost_children_2; $showValue = $value; $useAdultType = $object->cost_children_2_use_adult_type; $useAdultRate = $object->cost_children_2_use_adult_rate; }
                                            else if ($rows[$v]->code == 'cost_children_3') { $value = $object->cost_children_3; $showValue = $value; $useAdultType = $object->cost_children_3_use_adult_type; $useAdultRate = $object->cost_children_3_use_adult_rate; }
                                            else if ($rows[$v]->code == 'price') { $value = $object->price_adult; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_1') { $value = $object->price_children_1; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_2') { $value = $object->price_children_2; $showValue = $value; }
                                            else if ($rows[$v]->code == 'price_children_3') { $value = $object->price_children_3; $showValue = $value; }
                                            else if ($rows[$v]->code == 'allotment') { $value = $object->allotment; $showValue = $value; }
                                            else if ($rows[$v]->code == 'allotment_sold') { $value = $object->allotment_sold; $showValue = $value; }
                                            else if ($rows[$v]->code == 'allotment_base') { $value = $object->allotment_base; $showValue = $value; }
                                            else if ($rows[$v]->code == 'release') { $value = $object->release; $showValue = $value; }
                                            else if ($rows[$v]->code == 'stop_sale') { $value = $object->stop_sale; $showValue = ''; if ($object->stop_sale == 1) $showValue = '<span class="stop-sales">SS</span>'; else if ($object->stop_sale == 2) $showValue = '<span class="on-request">RQ</span>'; }
                                            else if ($rows[$v]->code == 'offer') { $auxDate = $i->format('Y-m-d'); $auxRoomId = $roomTypes[$r]->id; $value = isset($offerDates[$auxDate][$auxRoomId]) ? $offerDates[$auxDate][$auxRoomId] : ''; if ($value != '') { $showValue = '<span class="has-offer">X</span>'; }}
                                        }
                                    }
                                }
                                if ($rows[$v]->code == 'cost_children_1' || $rows[$v]->code == 'cost_children_2' || $rows[$v]->code == 'cost_children_3') {
                                    $table .=
                                        '<td class="column-setting ' . $usableClass . '" ' .
                                        'data="' . $value . '" ' .
                                        'data-date="' . $i->format('Y-m-d') . '" ' .
                                        'data-measure-id="' . $rows[$v]->id . '"' .
                                        'data-measure-code="' . $rows[$v]->code . '"' .
                                        'data-room-type-id="' . $roomTypes[$r]->id . '" ' .
                                        'data-market-id="' . $market . '" ' .
                                        'data-use-adult-type="' . $useAdultType . '" ' .
                                        'data-use-adult-rate="' . $useAdultRate . '" ' .
                                        '>' . $showValue . '</td>';
                                }
                                else {
                                    $table .=
                                        '<td class="column-setting ' . $usableClass . '" ' .
                                        'data="' . $value . '" ' .
                                        'data-date="' . $i->format('Y-m-d') . '" ' .
                                        'data-measure-id="' . $rows[$v]->id . '"' .
                                        'data-measure-code="' . $rows[$v]->code . '"' .
                                        'data-room-type-id="' . $roomTypes[$r]->id . '" ' .
                                        'data-market-id="' . $market . '" ' .
                                        '>' . $showValue . '</td>';
                                }
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
                $this->response['operateMeasures'] = $operateMeasures;
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
            'location'=> Input::get('location'),
            'validFrom'=> Input::get('validFrom'),
            'validTo'=> Input::get('validTo'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new HotelContractExport($parameters), 'Hotel Contracts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}