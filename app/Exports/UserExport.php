<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class UserExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    private $parameters;

    public function __construct($parameters) {
        $this->parameters = $parameters;
    }

    public function query()
    {
        $users = DB::table('users')
            ->select(
                'users.username', 'roles.name as role', 'users.name', 'users.email',
                DB::raw('(CASE WHEN users.active = 1 THEN "Yes" ELSE "No" END) AS active')
            )
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->orderBy('users.username');

        if ($this->parameters['username'] != '') {
            $users->where('users.username', 'like', '%' . $this->parameters['username'] . '%');
        }
        if ($this->parameters['role'] != '') {
            $users->where('roles.id', $this->parameters['role']);
        }
        if ($this->parameters['name'] != '') {
            $users->where('users.name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if ($this->parameters['email'] != '') {
            $users->where('users.email', 'like', '%' . $this->parameters['email'] . '%');
        }
        if ($this->parameters['active'] != '') {
            $users->where('users.active', $this->parameters['active']);
        }

        $users->get();
        return $users;
    }

    public function headings(): array
    {
        return [
            'Username',
            'Role',
            'Name',
            'Email',
            'Enabled',
        ];
    }
}
