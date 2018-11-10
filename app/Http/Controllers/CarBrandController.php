<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\CarBrand;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class CarBrandController extends Controller
{
    private $response;
    private $uploadPath;

    public function __construct() {
        $this->middleware('auth');
        $this->uploadPath = public_path().'/assets/pages/img/car/uploads/';
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Car',
            1 => 'Model'
        );

        $categories = DB::table('car_categories')
            ->select('car_categories.id', 'car_categories.name', 'car_categories.description')
            ->where('car_categories.active', '1')
            ->orderBy('car_categories.name', 'asc')
            ->get();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuCar'] = 'selected';
        $data['submenuCarBrand'] = 'selected';
        $data['categories'] = $categories;
        $data['uploadDir'] = asset('assets/pages/img/car/uploads');
        $data['noImgSrc'] = asset('assets/pages/img/car/no-image-768x576.jpg');
        $data['currentDate'] = parent::currentDate();

        return view('car.brand')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $brands = DB::table('car_brands')
            ->select('car_brands.id', 'car_brands.image as preview', 'car_brands.name', 'car_brands.description', 'car_brands.active', 'car_categories.name as category', 'car_categories.id as category_id','car_brands.image')
            ->join('car_categories', 'car_brands.car_category_id', '=', 'car_categories.id')
            ->get();

        return DataTables::of($brands)->make(true);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $rules = array(
            'name' => 'required',
            'category-id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $brand = new CarBrand();
            $brand->name = Input::get('name');
            $brand->description = Input::get('description');
            $brand->car_category_id = Input::get('category-id');
            $brand->active = Input::get('active') == 1 ? true : false;

            if (Input::file('image')) {
                $image = Input::file('image');
                $brand->image = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($this->uploadPath, $brand->image);
            }

            try {
                $brand->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Model ' . $brand->name . ' created successfully.';
                $this->response['data'] = $brand;
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
        $imageOperation = Input::get('imageOperation');
        $rules = array(
            'name' => 'required',
            'category-id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $brand = CarBrand::find($id);
            $brand->name = Input::get('name');
            $brand->description = Input::get('description');
            $brand->car_category_id = Input::get('category-id');
            $brand->active = Input::get('active') == 1 ? true : false;
            $oldImage = $brand->image;

            if ($imageOperation != '0') {
                if ($oldImage != null && file_exists($this->uploadPath . $oldImage)) {
                    unlink($this->uploadPath . $oldImage);
                }
                if ($imageOperation == '2') {
                    $brand->image = null;
                }
                else if ($imageOperation == '1') {
                    $image = Input::file('image');
                    $brand->image = uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($this->uploadPath, $brand->image);
                }
            }

            try {
                $brand->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Model updated successfully.';
                $this->response['data'] = $brand;
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
        $brand = CarBrand::find($id);

        try {
            $brand->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Model ' . $brand->name . ' deleted successfully.';
            $this->response['data'] = $brand;

            if ($brand->image != null && file_exists($this->uploadPath . $brand->image)) {
                unlink($this->uploadPath . $brand->image);
            }
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function duplicate(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $id = Input::get('id');
        $brand = CarBrand::find($id);
        $new = $brand->replicate();
        $new->name = $new->name . ' DUPLICATED!';

        try {
            if ($brand->image != null && file_exists($this->uploadPath . $brand->image)) {
                $extension = explode('.', $brand->image);
                $new->image = uniqid() . '.' . $extension[1];
                copy($this->uploadPath . $brand->image, $this->uploadPath . $new->image);
            }

            $new->save();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Model ' . $brand->name . ' duplicated successfully.';
            $this->response['data'] = $new;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Something was wrong, please contact the system administrator.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }
}
