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

class HotelController extends Controller
{
    private $response;
    private $hotelChainController;
    private $uploadPath;
    private $uploadThumbnailPath;
    private $publishPath;
    private $publishThumbnailPath;

    public function __construct() {
        $this->middleware('auth');
        $this->hotelChainController = new HotelChainController();
        $this->uploadPath = public_path().'/assets/pages/img/hotel/uploads/';
        $this->uploadThumbnailPath = public_path().'/assets/pages/img/hotel/uploads/thumbnails/';
        $this->publishPath = asset('assets/pages/img/hotel/uploads');
        $this->publishThumbnailPath = asset('assets/pages/img/hotel/uploads/thumbnails');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Hotel',
            1 => 'Hotels'
        );

        $hotelsChain = $this->hotelChainController->actives();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuHotel'] = 'selected';
        $data['submenuHotel'] = 'selected';
        $data['hotelsChain'] = $hotelsChain;
        $data['publishPath'] = $this->publishPath;
        $data['publishThumbnailPath'] = $this->publishThumbnailPath;

        return view('hotel.hotel')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $records = DB::table('hotels')->count();
        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('hotels.id', 'hotels.name', 'hotels.category', 'hotel_hotels_chain.name', 'hotels.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchName = Input::get('columns')['1']['search']['value'];
        $searchActive = Input::get('columns')['4']['search']['value'];
        $hotels = array();

        $query = DB::table('hotels')
            ->select(
                'hotels.id', 'hotels.name', 'hotels.country_id', 'hotels.state_id',
                'hotels.city_id', 'country.name as country', 'state.name as state', 'city.name as city',
                'hotels.postal_code', 'hotels.address', 'hotels.category', 'hotels.hotel_chain_id as chain_id',
                'hotel_hotels_chain.name as chain', 'hotels.admin_phone', 'hotels.admin_fax', 'hotels.web_site',
                'hotels.turistic_licence', 'hotels.active', 'hotels.email', 'hotels.description')
            ->leftJoin('locations as country', 'country.id', '=', 'hotels.country_id')
            ->leftJoin('locations as state', 'state.id', '=', 'hotels.state_id')
            ->leftJoin('locations as city', 'city.id', '=', 'hotels.city_id')
            ->leftJoin('hotel_hotels_chain', 'hotel_hotels_chain.id', '=', 'hotels.hotel_chain_id');

        if(isset($searchName) && $searchName != '') {
            $query->where('hotels.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('hotels.active', '=', $searchActive);
        }
        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);

        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'name' => $r->name,
                'category' => $r->category,
                'chain' => $r->chain,
                'active' => $r->active,
                'hotel' => $r
            );
            $hotels[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $hotels
        );
        echo json_encode($data);
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
            $hotel = new Hotel();
            $hotel->name = Input::get('name');
            $hotel->description = Input::get('description');
            $hotel->country_id = Input::get('country-id');
            $hotel->state_id = Input::get('state-id');
            $hotel->city_id = Input::get('city-id');
            $hotel->postal_code = Input::get('postal-code');
            $hotel->address = Input::get('address');
            $hotel->category = Input::get('category');
            $hotel->hotel_chain_id = Input::get('hotel-chain-id');
            $hotel->admin_phone = Input::get('admin-phone');
            $hotel->admin_fax = Input::get('admin-fax');
            $hotel->web_site = Input::get('web-site');
            $hotel->turistic_licence = Input::get('turistic-licence');
            $hotel->active = Input::get('active') == 1 ? true : false;
            $hotel->email = Input::get('email');

            try {
                $hotel->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Hotel ' . $hotel->name . ' created successfully.';
                $this->response['data'] = $hotel;
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
            $hotel = Hotel::find($id);
            $hotel->name = Input::get('name');
            $hotel->description = Input::get('description');
            $hotel->country_id = Input::get('country-id');
            $hotel->state_id = Input::get('state-id');
            $hotel->city_id = Input::get('city-id');
            $hotel->postal_code = Input::get('postal-code');
            $hotel->address = Input::get('address');
            $hotel->category = Input::get('category');
            $hotel->hotel_chain_id = Input::get('hotel-chain-id');
            $hotel->admin_phone = Input::get('admin-phone');
            $hotel->admin_fax = Input::get('admin-fax');
            $hotel->web_site = Input::get('web-site');
            $hotel->turistic_licence = Input::get('turistic-licence');
            $hotel->active = Input::get('active') == 1 ? true : false;
            $hotel->email = Input::get('email');

            try {
                $hotel->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Hotel updated successfully.';
                $this->response['data'] = $hotel;
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
        $hotel = Hotel::find($id);

        try {
            $hotel->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Hotel ' . $hotel->name . ' deleted successfully.';
            $this->response['data'] = $hotel;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
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

    public function actives() {
        $hotels = DB::table('hotels')
            ->select(
                'hotels.id', 'hotels.name', 'hotels.country_id', 'hotels.state_id',
                'hotels.city_id', 'country.name as country', 'state.name as state', 'city.name as city',
                'hotels.postal_code', 'hotels.address', 'hotels.category', 'hotels.hotel_chain_id as chain_id',
                'hotel_hotels_chain.name as chain', 'hotels.admin_phone', 'hotels.admin_fax', 'hotels.web_site',
                'hotels.turistic_licence', 'hotels.active', 'hotels.email', 'hotels.description')
            ->leftJoin('locations as country', 'country.id', '=', 'hotels.country_id')
            ->leftJoin('locations as state', 'state.id', '=', 'hotels.state_id')
            ->leftJoin('locations as city', 'city.id', '=', 'hotels.city_id')
            ->leftJoin('hotel_hotels_chain', 'hotel_hotels_chain.id', '=', 'hotels.hotel_chain_id')
            ->where('hotels.active', '=', '1')
            ->orderBy('hotels.name', 'asc')
            ->get();
        return $hotels;
    }

    public function searchActive(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $query = '%' . Input::get('q') . '%';
        $hotels = DB::table('hotels')
            ->select(
                'hotels.id', 'hotels.name', 'hotels.country_id', 'hotels.state_id',
                'hotels.city_id', 'country.name as country', 'state.name as state', 'city.name as city',
                'hotels.postal_code', 'hotels.address', 'hotels.category', 'hotels.hotel_chain_id as chain_id',
                'hotel_hotels_chain.name as chain', 'hotels.admin_phone', 'hotels.admin_fax', 'hotels.web_site',
                'hotels.turistic_licence', 'hotels.active', 'hotels.email', 'hotels.description')
            ->leftJoin('locations as country', 'country.id', '=', 'hotels.country_id')
            ->leftJoin('locations as state', 'state.id', '=', 'hotels.state_id')
            ->leftJoin('locations as city', 'city.id', '=', 'hotels.city_id')
            ->leftJoin('hotel_hotels_chain', 'hotel_hotels_chain.id', '=', 'hotels.hotel_chain_id')
            ->where('hotels.active', '=', '1')
            ->where('hotels.name', 'like', $query)
            ->orderBy('hotels.name', 'asc')
            ->get();
        echo json_encode($hotels);
    }

    public function getById($id) {
        $hotel = DB::table('hotels')
            ->select(
                'hotels.id', 'hotels.name', 'hotels.country_id', 'hotels.state_id',
                'hotels.city_id', 'country.name as country', 'state.name as state', 'city.name as city',
                'hotels.postal_code', 'hotels.address', 'hotels.category', 'hotels.hotel_chain_id as chain_id',
                'hotel_hotels_chain.name as chain', 'hotels.admin_phone', 'hotels.admin_fax', 'hotels.web_site',
                'hotels.turistic_licence', 'hotels.active', 'hotels.email', 'hotels.description')
            ->leftJoin('locations as country', 'country.id', '=', 'hotels.country_id')
            ->leftJoin('locations as state', 'state.id', '=', 'hotels.state_id')
            ->leftJoin('locations as city', 'city.id', '=', 'hotels.city_id')
            ->leftJoin('hotel_hotels_chain', 'hotel_hotels_chain.id', '=', 'hotels.hotel_chain_id')
            ->where('hotels.id', '=', $id)
            ->get();
        return $hotel;
    }

    public function getContractsById($id) {
        $contracts = DB::table('hotel_contracts')
            ->where('hotel_contracts.hotel_id', '=', $id)
            ->get();
        return $contracts;
    }

    public function searchContractActive(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $temp = '%' . Input::get('q') . '%';
        $hotels = DB::table('hotels')
            ->whereIn('hotels.id', function($query)
            {
                $query->select(DB::raw('hotel_contracts.hotel_id'))
                    ->from('hotel_contracts')
                    ->whereRaw('hotel_contracts.hotel_id = hotels.id');
            })
            ->where('hotels.name', 'like', $temp)
            ->orderBy('hotels.name', 'asc')
            ->get();

        foreach ($hotels as $h) {
            $contracts = $this->getContractsById($h->id);
            foreach ($contracts as $c) {
                $contract = HotelContract::find($c->id);
                $c->roomTypes = $contract->roomTypes;
            }
            $h->contracts = $contracts;
        }
        echo json_encode($hotels);
    }
}
