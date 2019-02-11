<?php

namespace App\Http\Controllers;

use App\Models\HotelContractRoomType;
use App\Models\HotelOffer;
use App\Models\HotelOfferContractRoomType;
use App\Models\HotelOfferRange;
use App\Models\HotelOfferType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HotelOfferController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $contractId = Input::get('contractId');
        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotel_offers.id', 'hotel_offers.name', 'hotel_offers.name', 'hotel_offers.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $offers = array();

        $query = HotelOffer::with([
            'offerType',
            'ranges',
            'rooms',
            'rooms.roomType'
        ])
            ->where('hotel_contract_id', $contractId);
        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'type' => isset($r->offerType) ? $r->offerType->name : '',
                'active' => $r->active,
                'object' => $r
            );
            $offers[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $offers
        );
        echo json_encode($data);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'name' => 'required',
            'offer-type' => 'required',
            'priority' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $roomTypes = json_decode(Input::get('room-types'), true);
            $ranges = Input::get('ranges');
            $ranges = json_decode($ranges);

            $offer = new HotelOffer();
            $offer->hotel_contract_id = Input::get('contractId');
            $offer->hotel_offer_type_id = Input::get('offer-type');
            $offer->priority = Input::get('priority');
            $offer->name = Input::get('name');
            $offer->description = Input::get('description');
            $offer->active = Input::get('active') == 1 ? 1 : 0;

            if ($offer->hotel_offer_type_id == 1) {
                $bookingDateFrom = Input::get('booking-date-from');
                $bookingDateTo = Input::get('booking-date-to');
                $paymentDate = Input::get('payment-date');
                if ($bookingDateFrom != '') {
                    $offer->booking_date_from = Carbon::createFromFormat('!d.m.Y', $bookingDateFrom)->format('Y-m-d');
                }
                if ($bookingDateTo != '') {
                    $offer->booking_date_to = Carbon::createFromFormat('!d.m.Y', $bookingDateTo)->format('Y-m-d');
                }
                if ($paymentDate != '') {
                    $offer->payment_date = Carbon::createFromFormat('!d.m.Y', $paymentDate)->format('Y-m-d');
                }
                $offer->non_refundable = Input::get('non-refundable') == 1 ? 1 : 0;
                $offer->apply_with_other_offers = Input::get('apply-with-other-offers') == 1 ? 1 : 0;
                $offer->percentage_due = Input::get('percentage-due');
                $offer->discount = Input::get('discount');
                $offer->discount_type = Input::get('discount-type');
                $offer->minimum_stay = Input::get('minimum-stay');
            }

            DB::beginTransaction();
            try {
                $offer->save();
                foreach($ranges as $range) {
                    $start = Carbon::createFromFormat('d.m.Y', $range->from);
                    $end = Carbon::createFromFormat('d.m.Y', $range->to);
                    $offerRange = new HotelOfferRange();
                    $offerRange->hotel_offer_id = $offer->id;
                    $offerRange->from = $start->format('Y-m-d');
                    $offerRange->to = $end->format('Y-m-d');
                    $offerRange->save();
                }
                foreach($roomTypes as $roomType) {
                    $contractRoomType = HotelContractRoomType::where('hotel_contract_id', $offer->hotel_contract_id)
                        ->where('hotel_room_type_id', $roomType)->first();
                    $offerContractRoomtype = new HotelOfferContractRoomType();
                    $offerContractRoomtype->hotel_offer_id = $offer->id;
                    $offerContractRoomtype->hotel_contract_room_type_id = $contractRoomType->id;
                    $offerContractRoomtype->hotel_room_type_id = $contractRoomType->hotel_room_type_id;
                    $offerContractRoomtype->save();
                }
                DB::commit();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Offer created successfully.';
                $this->response['data'] = $offer;
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
            'id' => 'required',
            'name' => 'required',
            'offer-type' => 'required',
            'priority' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $roomTypes = json_decode(Input::get('room-types'), true);
            $ranges = Input::get('ranges');
            $ranges = json_decode($ranges);

            $offer = HotelOffer::where('id', $id)->first();

            $offer->hotel_offer_type_id = Input::get('offer-type');
            $offer->priority = Input::get('priority');
            $offer->name = Input::get('name');
            $offer->description = Input::get('description');
            $offer->active = Input::get('active') == 1 ? 1 : 0;

            if ($offer->hotel_offer_type_id == 1) {
                $bookingDateFrom = Input::get('booking-date-from');
                $bookingDateTo = Input::get('booking-date-to');
                $paymentDate = Input::get('payment-date');
                if ($bookingDateFrom != '') {
                    $offer->booking_date_from = Carbon::createFromFormat('!d.m.Y', $bookingDateFrom)->format('Y-m-d');
                }
                if ($bookingDateTo != '') {
                    $offer->booking_date_to = Carbon::createFromFormat('!d.m.Y', $bookingDateTo)->format('Y-m-d');
                }
                if ($paymentDate != '') {
                    $offer->payment_date = Carbon::createFromFormat('!d.m.Y', $paymentDate)->format('Y-m-d');
                }
                $offer->non_refundable = Input::get('non-refundable') == 1 ? 1 : 0;
                $offer->apply_with_other_offers = Input::get('apply-with-other-offers') == 1 ? 1 : 0;
                $offer->percentage_due = Input::get('percentage-due');
                $offer->discount = Input::get('discount');
                $offer->discount_type = Input::get('discount-type');
                $offer->minimum_stay = Input::get('minimum-stay');
            }

            DB::beginTransaction();
            try {
                $offer->save();
                HotelOfferRange::where('hotel_offer_id', $offer->id)->delete();
                HotelOfferContractRoomType::where('hotel_offer_id', $offer->id)->delete();
                foreach($ranges as $range) {
                    $start = Carbon::createFromFormat('d.m.Y', $range->from);
                    $end = Carbon::createFromFormat('d.m.Y', $range->to);
                    $offerRange = new HotelOfferRange();
                    $offerRange->hotel_offer_id = $offer->id;
                    $offerRange->from = $start->format('Y-m-d');
                    $offerRange->to = $end->format('Y-m-d');
                    $offerRange->save();
                }
                foreach($roomTypes as $roomType) {
                    $contractRoomType = HotelContractRoomType::where('hotel_contract_id', $offer->hotel_contract_id)
                        ->where('hotel_room_type_id', $roomType)->first();
                    $offerContractRoomtype = new HotelOfferContractRoomType();
                    $offerContractRoomtype->hotel_offer_id = $offer->id;
                    $offerContractRoomtype->hotel_contract_room_type_id = $contractRoomType->id;
                    $offerContractRoomtype->hotel_room_type_id = $contractRoomType->hotel_room_type_id;
                    $offerContractRoomtype->save();
                }
                DB::commit();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Offer updated successfully.';
                $this->response['data'] = $offer;
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
        $offer = HotelOffer::find($id);

        try {
            $offer->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Offer ' . $offer->name . ' deleted successfully.';
            $this->response['data'] = $offer;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed probably the hotel is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }
}
