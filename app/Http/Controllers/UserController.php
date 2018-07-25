<?php

namespace App\Http\Controllers;

use App\Market;
use App\Role;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Exception;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $response;
    private $marketController;

    public function __construct() {
        $this->middleware('auth');
        $this->marketController = new MarketController();
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $breadcrumb = array(
            0 => 'Administration',
            1 => 'Users'
        );

        $roles = DB::table('roles')
            ->select('roles.id', 'roles.description', 'roles.name')
            ->get();

        $markets = $this->marketController->actives();

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuUser'] = 'selected';
        $data['roles'] = $roles;
        $data['markets'] = $markets;

        return view('administration.user')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $users = DB::table('users')
            ->select(
                'users.id', 'users.name', 'users.email', 'users.username', 'roles.name as role',
                'roles.id as role_id', 'users.active', 'markets.name as market', 'markets.id as market_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('markets', 'users.market_id', '=', 'markets.id')
            ->get();
        return DataTables::of($users)->make(true);
    }

    public function create(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $rules = array(
            'name' => 'required|min:2',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'role-id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $user = new User();
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = bcrypt(Input::get('password'));
            $user->username = Input::get('username');
            $user->active = Input::get('active') == 1 ? true : false;

            try {
                $role = Role::find(Input::get('role-id'));
                if($role->name == 'client') {
                    $market = Market::find(Input::get('market'));
                    if ($market === null)
                        throw new Exception('Market required.');
                    else
                        $user->market_id = $market->id;
                }
                else $user->market_id = 0;
                $user->save();
                $user->roles()->attach(Input::get('role-id'));
                $this->response['status'] = 'success';
                $this->response['message'] = 'User ' . $user->username . ' created successfully.';
                $this->response['data'] = $user;
            }
            catch (QueryException $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error, probably the email or username already exist.';
                $this->response['errors'] = $e->errorInfo[2];
            }
            catch (Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Validation errors.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        echo json_encode($this->response);
    }

    public function update(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $id = Input::get('id');
        $rules = array(
            'name' => 'required|min:2',
            'email' => 'required|email',
            'username' => 'required',
            'role-id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Validation errors.';
            $this->response['errors'] = $validator->errors();
        }
        else {
            $user = User::find($id);
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->username = Input::get('username');
            $user->active = Input::get('active') == 1 ? true : false;
            if (Input::get('password') != '')
                $user->password = bcrypt(Input::get('password'));

            try {
                $role = Role::find(Input::get('role-id'));
                if($role->name == 'client') {
                    $market = Market::find(Input::get('market'));
                    if ($market === null)
                        throw new Exception('Market required.');
                    else
                        $user->market_id = $market->id;
                }
                else $user->market_id = 0;
                $user->save();
                $user->roles()->sync(Input::get('role-id'));
                $this->response['status'] = 'success';
                $this->response['message'] = 'User updated successfully.';
                $this->response['data'] = $user;
            }
            catch (QueryException $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error, probably the email or username already exist.';
                $this->response['errors'] = $e->errorInfo[2];
            }
            catch (Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Validation errors.';
                $this->response['errors'] = $e->getMessage();
            }
        }
        echo json_encode($this->response);
    }

    public function delete(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $id = Input::get('id');
        $user = User::find($id);

        try {
            $user->delete();
            $user->roles()->sync(Input::get('role-id'));
            $this->response['status'] = 'success';
            $this->response['message'] = 'User ' . $user->username . ' deleted successfully.';
            $this->response['data'] = $user;
        }
        catch (QueryException $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Database error.';
            $this->response['errors'] = $e->errorInfo[2];
        }
        echo json_encode($this->response);
    }
}
