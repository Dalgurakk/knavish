<?php

namespace App\Http\Controllers;

use App\Exports\LocationExport;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $breadcrumb = array(
            0 => 'Nomenclators',
            1 => 'Locations'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuNomenclators'] = 'selected';
        $data['submenuLocations'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('administration.location')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $parentId = Input::get('id');
        $nodes = array();

        try {
            if ($parentId == '#') {
                $locations = Location::whereIsRoot()->get();
                foreach ($locations as $l) {
                    $item = array(
                        'id' => $l->id,
                        'text' => $l->name,
                        'data' => $l,
                        'children' => ($l->_rgt - $l->_lft > 1) ? true : false,
                        'icon' => $l->icon
                    );
                    $nodes[] = (object)$item;
                }
            }
            else {
                $locations = DB::table('locations')
                    ->select('locations.id', 'locations.code', 'locations.name', 'locations.active', 'locations._rgt', 'locations._lft', 'locations.icon')
                    ->where('locations.parent_id', '=', $parentId)
                    ->get();

                foreach ($locations as $l) {
                    $item = array(
                        'id' => $l->id,
                        'text' => $l->name,
                        'data' => $l,
                        'children' => ($l->_rgt - $l->_lft > 1) ? true : false,
                        'icon' => $l->icon
                    );
                    $nodes[] = (object)$item;
                }
            }
        }
        catch (\Exception $e) {
            $nodes = array();
        }
        echo json_encode($nodes);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

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
            $location = new Location();
            $location->code = Input::get('code');
            $location->name = Input::get('name');
            $location->icon = (Input::get('icon') != 'empty') ? ('glyphicon ' . Input::get('icon')) : 'glyphicon glyphicon-question-sign';
            $location->active = Input::get('active') == 1 ? 1 : 0;

            $parentId = Input::get('parent-id');
            if ($parentId != 0) {
                $location->parent_id = $parentId;
            }

            try {
                $location->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Location ' . $location->code . ': ' . $location->name . ' created successfully.';
                $this->response['data'] = $location;
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
        $request->user()->authorizeRoles(['administrator']);

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
            $location = Location::find($id);
            $location->code = Input::get('code');
            $location->name = Input::get('name');
            $location->icon = (Input::get('icon') != 'empty') ? ('glyphicon ' . Input::get('icon')) : 'glyphicon glyphicon-question-sign';
            $location->active = Input::get('active') == 1 ? 1 : 0;

            try {
                $location->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Location updated successfully.';
                $this->response['data'] = $location;
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
        $request->user()->authorizeRoles(['administrator']);

        $id = Input::get('id');
        $location = Location::find($id);

        try {
            $location->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Location ' . $location->name . ' deleted successfully.';
            $this->response['data'] = $location;
        }
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the location is in use.';
            $this->response['errors'] = $e->getMessage();
        }
        echo json_encode($this->response);
    }

    public function actives(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $parentId = Input::get('id');
        $nodes = array();

        try {
            if ($parentId == '#') {
                $locations = Location::whereIsRoot()->get();
                foreach ($locations as $l) {
                    if ($l->active == 1) {
                        $item = array(
                            'id' => $l->id,
                            'text' => $l->name,
                            'data' => $l,
                            'children' => ($l->_rgt - $l->_lft > 1) ? true : false,
                            'icon' => $l->icon
                        );
                        $nodes[] = (object)$item;
                    }
                }
            }
            else {
                $locations = DB::table('locations')
                    ->select('locations.id', 'locations.code', 'locations.name', 'locations.active', 'locations._rgt', 'locations._lft', 'locations.icon')
                    ->where('locations.parent_id', '=', $parentId)
                    ->where('locations.active', '=', '1')
                    ->get();

                foreach ($locations as $l) {
                    $item = array(
                        'id' => $l->id,
                        'text' => $l->name,
                        'data' => $l,
                        'children' => ($l->_rgt - $l->_lft > 1) ? true : false,
                        'icon' => $l->icon
                    );
                    $nodes[] = (object)$item;
                }
            }
        }
        catch (\Exception $e) {
            $nodes = array();
        }
        echo json_encode($nodes);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:E4',
            'headerText' => 'Locations',
            'cellRange' => 'A6:E6'
        );

        $parameters = array(
            'settings' => $settings
        );
        return Excel::download(new LocationExport($parameters), 'Locations.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
