<?php

namespace App\Http\Controllers;

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

    public function __construct() {
        $this->middleware('auth');
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

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuUser'] = 'selected';
        $data['roles'] = $roles;

        return view('administration.user')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $users = DB::table('users')
            ->select(
                'users.id', 'users.name', 'users.email', 'users.username', 'roles.name as role',
                'roles.id as role_id', 'users.active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
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

    public function getClientsActivesByName(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $string = '%' . Input::get('q') . '%';
        $clients = DB::table('users')
            ->select(
                'users.id', 'users.name', 'users.email', 'users.username', 'roles.name as role',
                'roles.id as role_id', 'users.active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'client')
            ->where('users.username', 'like', '%' . $string . '%')
            ->where('active', '1')
            ->get();
        echo json_encode($clients);
    }
}
