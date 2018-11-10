<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

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
        $data['currentDate'] = parent::currentDate();

        return view('administration.user')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array('users.id', 'users.username', 'roles.name', 'roles.id', 'users.name', 'users.email', 'users.active');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchUsername = Input::get('columns')['1']['search']['value'];
        $searchRole = Input::get('columns')['2']['search']['value'];
        $searchName = Input::get('columns')['4']['search']['value'];
        $searchEmail = Input::get('columns')['5']['search']['value'];
        $searchActive = Input::get('columns')['6']['search']['value'];
        $users = array();

        $query = User::with('roles');
        if(isset($searchUsername) && $searchUsername != '') {
            $query->where('users.username', 'like', '%' . $searchUsername . '%');
        }
        if(isset($searchRole) && $searchRole != '') {
            $query->whereHas('roles', function ($query) use ($searchRole) {
                $query->where('name', 'like', '%' . $searchRole . '%');
            });
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('users.name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchEmail) && $searchEmail != '') {
            $query->where('users.email', 'like', '%' . $searchEmail . '%');
        }
        if(isset($searchActive) && $searchActive != '') {
            $query->where('users.active', '=', $searchActive);
        }

        $records = $query->count();

        $query
            ->orderBy($columns[$orderBy], $orderDirection)
            ->offset($offset)
            ->limit($limit);
        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'username' => $r->username,
                'role' => $r->roles[0]->name,
                'role_id' => $r->roles[0]->id,
                'name' => $r->name,
                'email' => $r->email,
                'active' => $r->active
            );
            $users[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $users
        );
        echo json_encode($data);
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
            catch (\Exception $e) {
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
            catch (\Exception $e) {
                $this->response['status'] = 'error';
                $this->response['message'] = 'Database error, probably the email or username already exist.';
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
        catch (\Exception $e) {
            $this->response['status'] = 'error';
            $this->response['message'] = 'The operation can not be completed, probably the user is in use.';
            $this->response['errors'] = $e->getMessage();
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
            ->where('users.username', 'like', $string)
            ->where('active', '1')
            ->get();
        echo json_encode($clients);
    }

    public function toExcel(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $settings = array(
            'headerRange' => 'A4:F4',
            'headerText' => 'Users',
            'cellRange' => 'A6:F6'
        );

        $parameters = array(
            'username' => Input::get('username'),
            'role' => Input::get('role'),
            'name'=> Input::get('name'),
            'email' => Input::get('email'),
            'active' => Input::get('active'),
            'settings' => $settings
        );
        return Excel::download(new UserExport($parameters), 'Users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
