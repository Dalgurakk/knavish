<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;
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
        $data['currentDate'] = parent::currentDate();

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
            $category->active = Input::get('active') == 1 ? 1 : 0;

            try {
                $category->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Category ' . $category->name . ' created successfully.';
                $this->response['data'] = $category;
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
            $category->active = Input::get('active') == 1 ? 1 : 0;

            try {
                $category->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Category updated successfully.';
                $this->response['data'] = $category;
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
        $category = CarCategory::find($id);

        try {
            $category->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Category ' . $category->name . ' deleted successfully.';
            $this->response['data'] = $category;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the category is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }
}
