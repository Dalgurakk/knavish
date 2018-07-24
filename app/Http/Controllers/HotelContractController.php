<?php

namespace App\Http\Controllers;

use App\HotelContract;
use App\HotelImage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Hotel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Carbon;

class HotelContractController extends Controller
{
    private $response;
    private $hotelController;
    private $hotelPaxTypeController;
    private $hotelBoardTypeController;

    public function __construct() {
        $this->middleware('auth');
        $this->hotelController = new HotelController();
        $this->hotelPaxTypeController = new HotelPaxTypeController();
        $this->hotelBoardTypeController = new HotelBoardTypeController();
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Hotels'
        );

        $hotels = $this->hotelController->actives();
        $paxTypes = $this->hotelPaxTypeController->actives();
        $boardTypes = $this->hotelBoardTypeController->actives();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuContract'] = 'selected';
        $data['hotels'] = $hotels;
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
                'hotel_contracts.valid_to', 'hotel_contracts.active', 'hotel_contracts.hotel_id', 'hotel_contracts.status')
            ->leftJoin('hotels', 'hotels.id', '=', 'hotel_contracts.hotel_id');

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
            $hotel = $this->hotelController->getById($r->hotel_id)[0];
            $r->hotelData = $hotel;

            if ($r->status == '0')
                $r->status_text = 'Draft';
            else if ($r->status == '1')
                $r->status_text = 'Signed';
            else $r->status_text = '';

            $validFrom = Carbon::createFromFormat('Y-m-d', $r->valid_from);
            $r->valid_from = $validFrom->format('d-m-Y');
            $validTo = Carbon::createFromFormat('Y-m-d', $r->valid_to);
            $r->valid_to = $validTo->format('d-m-Y');

            $contract = HotelContract::find($r->id);
            $r->paxTypes = $contract->paxTypes;
            $r->boardTypes = $contract->boardTypes;
            $r->roomTypes = $contract->roomTypes;

            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'hotel' => $r->hotel,
                'valid_from' => $r->valid_from,
                'valid_to' => $r->valid_to,
                'active' => $r->active,
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

    public function uploadImage(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'files' => 'required',
            'files.*' => 'image|mimes:jpeg,png,jpg'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $files = array();
            if($request->hasfile('files')) {
                foreach($request->file('files') as $file) {
                    $hotelImage = new HotelImage();
                    $hotelImage->hotel_id = Input::get('id');
                    $hotelImage->name = $file->getClientOriginalName();
                    $hotelImage->image = uniqid() . '.' . $file->getClientOriginalExtension();
                    $hotelImage->size = $file->getClientSize();
                    $path = public_path('assets/pages/img/hotel/uploads/thumbnails/' . $hotelImage->image);
                    $thumbnail = Image::make($file->getRealPath());
                    $thumbnail->resize(100, 78)->save($path);
                    $hotelImage->mime = $thumbnail->mime();
                    $hotelImage->save();
                    $file->move($this->uploadPath, $hotelImage->image);

                    $item = new \stdClass();
                    $item->name = $hotelImage->name;
                    $item->size = $hotelImage->size;
                    $item->type = $hotelImage->mime;
                    $item->url = $this->publishPath . '/' . $hotelImage->image;
                    $item->thumbnailUrl = $this->publishThumbnailPath . '/' . $hotelImage->image;
                    $item->deleteUrl = route('hotel.delete.image') . '?id=' . $hotelImage->image;
                    $item->deleteType = "POST";
                    $files[] = $item;

                    $result = new \stdClass();
                    $result->files = $files;
                    $this->response = $result;
                }
            }
            echo json_encode($this->response);
        }
    }

    public function deleteImage(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $image = Input::get('id');
        $hotelImage = HotelImage::where('image', $image)->first();
        $hotelImage->delete();

        if ($hotelImage->image != null && file_exists($this->uploadPath . $hotelImage->image)) {
            unlink($this->uploadPath . $hotelImage->image);
        }
        if ($hotelImage->image != null && file_exists($this->uploadThumbnailPath . $hotelImage->image)) {
            unlink($this->uploadThumbnailPath . $hotelImage->image);
        }
        $result = array(
            $hotelImage->image => true
        );
        $this->response = $result;
        echo json_encode($this->response);
    }

    public function images(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $hotel = Hotel::find($id);
        $hotelImages = $hotel->images;
        $files = array();
        foreach ($hotelImages as $hotelImage) {
            $item = new \stdClass();
            $item->name = $hotelImage->name;
            $item->size = $hotelImage->size;
            $item->type = $hotelImage->mime;
            $item->url = $this->publishPath . '/' . $hotelImage->image;
            $item->thumbnailUrl = $this->publishThumbnailPath . '/' . $hotelImage->image;
            $item->deleteUrl = route('hotel.delete.image') . '?id=' . $hotelImage->image;
            $item->deleteType = "POST";
            $files[] = $item;
        }
        $result = new \stdClass();
        $result->files = $files;
        $this->response = $result;
        echo json_encode($this->response);
    }

    public function hotelActive() {
        $hotels = DB::table('hotels')
            ->where('hotels.active', '=', '1')
            ->orderBy('hotels.name', 'asc')
            ->get();
        return $hotels;
    }
}
