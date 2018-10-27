<?php

namespace App\Exports;

use App\User;

class UserExport extends GeneralExport
{
    public function collection() {
        $query = User::with(['roles']);

        if ($this->parameters['username'] != '') {
            $query->where('users.username', 'like', '%' . $this->parameters['username'] . '%');
        }
        if($this->parameters['role'] != '') {
            $id = $this->parameters['role'];
            $query->whereHas('roles', function ($query) use ($id) {
                $query->where('role_id', $id);
            });
        }
        if ($this->parameters['name'] != '') {
            $query->where('users.name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if ($this->parameters['email'] != '') {
            $query->where('users.email', 'like', '%' . $this->parameters['email'] . '%');
        }
        if ($this->parameters['active'] != '') {
            $query->where('users.active', $this->parameters['active']);
        }
        $items = $query->orderBy('username')->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            return [
                'index' => $index + 1,
                'username' => $item->username,
                'role' => $item->roles[0]->description,
                'name' => $item->username,
                'email' => $item->email,
                'enabled' => $item->active == '1' ? 'Yes' : 'No'
            ];
        });
        $this->rangeContent = parent::getContentRange($this->records);
        return $filtered;
    }

    public function headings(): array
    {
        return [
            'Number',
            'Username',
            'Role',
            'Name',
            'Email',
            'Enabled',
        ];
    }
}
