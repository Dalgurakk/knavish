<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\HotelPaxType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

        $breadcrumb = array(
            0 => 'Administration',
            1 => 'Locations'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuLocations'] = 'selected';

        return view('administration.location')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

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
        catch (QueryException $e) {
            $nodes = array();
        }
        echo json_encode($nodes);
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
            $location = new Location();
            $location->code = Input::get('code');
            $location->name = Input::get('name');
            $location->icon = (Input::get('icon') != 'empty') ? ('glyphicon ' . Input::get('icon')) : 'glyphicon glyphicon-question-sign';
            $location->active = Input::get('active') == 1 ? true : false;

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
            $location = Location::find($id);
            $location->code = Input::get('code');
            $location->name = Input::get('name');
            $location->icon = (Input::get('icon') != 'empty') ? ('glyphicon ' . Input::get('icon')) : 'glyphicon glyphicon-question-sign';
            $location->active = Input::get('active') == 1 ? true : false;

            try {
                $location->save();
                $this->response['status'] = 'success';
                $this->response['message'] = 'Location updated successfully.';
                $this->response['data'] = $location;
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
        $location = Location::find($id);

        try {
            $location->delete();
            $this->response['status'] = 'success';
            $this->response['message'] = 'Location ' . $location->code . ': ' . $location->name . ' deleted successfully.';
            $this->response['data'] = $location;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }

    public function actives(Request $request) {
        $request->user()->authorizeRoles(['administrator', 'commercial']);

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
        catch (QueryException $e) {
            $nodes = array();
        }
        echo json_encode($nodes);
    }
}
