<?php

namespace App\Http\Controllers;

use App\HotelBoardType;
use App\HotelContract;
use App\HotelContractSetting;
use App\HotelMeasure;
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
        $measures = HotelMeasure::where('active', '1')->orderBy('id', 'asc')->get();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuContract'] = 'selected';
        $data['paxTypes'] = $paxTypes;
        $data['boardTypes'] = $boardTypes;
        $data['measures'] = $measures;

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

        foreach ($result as $r) {
            $query = HotelContract::with([
                'hotel', 'hotel.hotelChain', 'hotel.country', 'hotel.state', 'hotel.city',
                'roomTypes', 'paxTypes', 'boardTypes', 'measures'])
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
                DB::commit();

                $this->response['status'] = 'success';
                $this->response['message'] = 'Contract ' . $contract->name . ' created successfully.';
                $this->response['data'] = $contract;
            }
            catch (QueryException $e) {
                DB::rollBack();
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
            'name' => 'required',
            'id' => 'required',
            'hotel-id' => 'required',
            'valid-from' => 'required|date|date_format:"d.m.Y"|before:valid-to',
            'valid-to' => 'required|date|date_format:"d.m.Y"|after:valid-from',
            'roomTypes' => 'required|json',
            'boardTypes' => 'required|json',
            'paxTypes' => 'required|json',
            'measures' => 'required|json'
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
            $contract->valid_from = Carbon::createFromFormat('d.m.Y', Input::get('valid-from'))->format('Y-m-d');
            $contract->valid_to = Carbon::createFromFormat('d.m.Y', Input::get('valid-to'))->format('Y-m-d');
            $contract->active = Input::get('active') == 1 ? true : false;
            $contract->status = Input::get('status');

            DB::beginTransaction();
            try {
                $contract->save();
                $roomTypes = json_decode(Input::get('roomTypes'), true);
                $boardTypes = json_decode(Input::get('boardTypes'));
                $paxTypes = json_decode(Input::get('paxTypes'));
                $temp = json_decode(Input::get('measures'));
                $measures = array(1, 2);
                foreach ($temp as $key => $val) {
                    if($val == 1 || $val == 2) continue;
                    else $measures[] = $val;
                }
                $contract->roomTypes()->sync($roomTypes);
                $contract->boardTypes()->sync($boardTypes);
                $contract->paxTypes()->sync($paxTypes);
                $contract->measures()->sync($measures);

                DB::commit();
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
        }
        echo json_encode($this->response);
    }

    public function delete(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $contract = HotelContract::find($id);

        DB::beginTransaction();
        try {
            $contract->paxTypes()->detach();
            $contract->boardTypes()->detach();
            $contract->roomTypes()->detach();
            $contract->measures()->detach();
            $contract->settings()->delete();
            $contract->delete();
            DB::commit();
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
        echo json_encode($this->response);
    }

    public function settings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Settings'
        );
        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuSettings'] = 'selected';
        $data['contract_id'] = null;

        $id = Input::get('id');
        if ($id != '') {
            $data['contract_id'] = $id;
        }
        return view('hotel.setting')->with($data);
    }

    public function getContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('contractId');
        $query = HotelContract::with(['hotel', 'roomTypes', 'measures']);

        if($id != '') {
            $query->where('id', $id);
        }
        try {
            $contract = $query->first();
            if ($contract === null) {
                $this->response['status'] = 'error';
                $this->response['data'] = null;
                $this->response['message'] = 'There are no contracts available.';
            }
            else
                $this->response['data'] = $contract;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }

    public function getByName(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $string = '%' . Input::get('q') . '%';
        $contracts = HotelContract::with(['hotel', 'roomTypes', 'measures'])
            ->where('name', 'like', $string)
            ->orderBy('name', 'asc')
            ->get();
        echo json_encode($contracts);
    }

    public function saveSettings(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'setting-from' => 'required|date|date_format:"d.m.Y"',
            'setting-to' => 'required|date|date_format:"d.m.Y"'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $contractId = Input::get('contract-id');
            $from = Input::get('setting-from');
            $to = Input::get('setting-to');
            $setPrice = Input::get('set-price');
            $setAllotment = Input::get('set-allotment');
            $setCost = Input::get('set-cost');
            $setStopSale = Input::get('set-stop_sale');
            $setOffer = Input::get('set-offer');
            $setRelease = Input::get('set-release');
            $setSupplement = Input::get('set-supplement');
            $roomTypeId = Input::get('room-type-id');

            $start = Carbon::createFromFormat('d.m.Y', $from);
            $end = Carbon::createFromFormat('d.m.Y', $to);
            $contract = HotelContract::with('roomTypes', 'measures')->where('id', $contractId)->first();

            for ($m = $start; $m->lessThanOrEqualTo($end); $m->addDay()) {
                $contractSetting = HotelContractSetting::where('hotel_contract_id', $contractId)
                    ->where('date', $m->format('Y-m-d'))->first();
                if ($contractSetting === null) {
                    $contractSetting = new HotelContractSetting();
                    $contractSetting->hotel_contract_id = $contract->id;
                    $contractSetting->date = $m->format('Y-m-d');
                    $settings = array();
                    if($setPrice != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setPrice;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('price');
                        $settings[] = $obj;
                    }
                    if($setAllotment != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setAllotment;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('allotment');
                        $settings[] = $obj;
                    }
                    if($setCost != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setCost;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('cost');
                        $settings[] = $obj;
                    }
                    if($setStopSale != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setStopSale;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('stop_sale');
                        $settings[] = $obj;
                    }
                    if($setOffer != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setOffer;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('offer');
                        $settings[] = $obj;
                    }
                    if($setRelease != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setRelease;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('release');
                        $settings[] = $obj;
                    }
                    if($setSupplement != '') {
                        $obj = new \stdClass();
                        $obj->measure_id = $setSupplement;
                        $obj->room_type_id = $roomTypeId;
                        $obj->value = Input::get('supplement');
                        $settings[] = $obj;
                    }
                    $contractSetting->settings = json_encode($settings);
                    $contractSetting->save();
                }
                else {
                    $settings = json_decode($contractSetting->settings);

                    if ($setPrice != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setPrice && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('price');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setPrice;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('price');
                            $settings[] = $obj;
                        }
                    }
                    if ($setAllotment != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setAllotment && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('allotment');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setAllotment;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('allotment');
                            $settings[] = $obj;
                        }
                    }
                    if ($setCost != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setCost && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('cost');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setCost;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('cost');
                            $settings[] = $obj;
                        }
                    }
                    if ($setStopSale != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setStopSale && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('stop_sale');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setStopSale;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('stop_sale');
                            $settings[] = $obj;
                        }
                    }
                    if ($setOffer != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setOffer && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('offer');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setOffer;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('offer');
                            $settings[] = $obj;
                        }
                    }
                    if ($setRelease != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setRelease && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('release');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setRelease;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('release');
                            $settings[] = $obj;
                        }
                    }
                    if ($setSupplement != '') {
                        $isInSettings = false;
                        for ($i = 0; $i < count($settings); $i ++) {
                            if ($settings[$i]->measure_id == $setSupplement && $settings[$i]->room_type_id == $roomTypeId){
                                $settings[$i]->value = Input::get('supplement');
                                $isInSettings = true;
                                break;
                            }
                        }
                        if (!$isInSettings) {
                            $obj = new \stdClass();
                            $obj->measure_id = $setSupplement;
                            $obj->room_type_id = $roomTypeId;
                            $obj->value = Input::get('supplement');
                            $settings[] = $obj;
                        }
                    }
                    $contractSetting->settings = json_encode($settings);
                    $contractSetting->save();
                }
            }
            $this->response['status'] = 'success';
            $this->response['message'] = 'Petition executed successfully.';
            $this->response['data'] = $contractSetting;
        }
        echo json_encode($this->response);
    }

    public function settingsByContract(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $from = Input::get('from');
        $to = Input::get('to');
        $start = Carbon::createFromFormat('d.m.Y', $from)->startOfMonth();
        $end = Carbon::createFromFormat('d.m.Y', $to)->endOfMonth();

        $contract = HotelContract::with('settings', 'measures', 'roomTypes')->where('id', $id)->first();
        $contractSettings = $contract->settings;
        $settings = array();

        foreach ($contractSettings as $s) {
            $settings[$s->date] = json_decode($s->settings);
        }

        if ($contract === null) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Invalid contract.';
        }
        else {
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
                            '<a href="" class="fullscreen"> </a>' .
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
                                $dateSettings = $settings[$i->format('Y-m-d')];
                                if (is_array($dateSettings) && count($dateSettings) > 0) {
                                    foreach ($dateSettings as $d) {
                                        if ($d->measure_id == $rows[$v]->id && $d->room_type_id == $roomTypes[$r]->id) {
                                            $value = $d->value;
                                            break;
                                        }
                                    }
                                }
                            }
                            $table .=
                                        '<td class="column-setting item-setting" data-date="' . $i->format('Y-m-d') . '" ' .
                                        'data-measure-id="' . $rows[$v]->id . '" data-room-type-id="' . $roomTypes[$r]->id . '" ' .
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
        echo json_encode($this->response);
    }
}
