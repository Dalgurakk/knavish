<?php

namespace App\Http\Controllers;

use App\CarBrand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\CarCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class CarCategoryController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Car',
            1 => 'Category'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuCar'] = 'selected';
        $data['submenuCarCategory'] = 'selected';

        return view('car.category')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $categories = DB::table('car_categories')
            ->select('car_categories.id', 'car_categories.name', 'car_categories.description', 'car_categories.active')
            ->get();

        return DataTables::of($categories)->make(true);
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
            $category = new CarCategory();
            $category->name = Input::get('name');
            $category->description = Input::get('description');
            $category->active = Input::get('active') == 1 ? true : false;

            try {
                $category->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Category ' . $category->name . ' created successfully.';
                $this->response['data'] = $category;
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
            $category = CarCategory::find($id);
            $category->name = Input::get('name');
            $category->description = Input::get('description');
            $category->active = Input::get('active') == 1 ? true : false;

            try {
                $category->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Category updated successfully.';
                $this->response['data'] = $category;
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
        $category = CarCategory::find($id);

        try {
            //$category->brands()->delete();
            $brand = CarBrand::where('car_category_id', '=', $category->id)->first();
            if ($brand === null) {
                $category->delete();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Category ' . $category->name . ' deleted successfully.';
                $this->response['data'] = $category;
            }
            else {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Category is in use, please check the car model: ' . $brand->name . '.';
            }
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }
}
