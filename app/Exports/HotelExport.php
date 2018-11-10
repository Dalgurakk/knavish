<?php

namespace App\Exports;

use App\Models\Hotel;

class HotelExport extends GeneralExport
{
    public function collection() {
        $query = Hotel::with(['hotelChain', 'country', 'state', 'city'])->orderBy('name');
        if ($this->parameters['name'] != '') {
            $query->where('name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if ($this->parameters['active'] != '') {
            $query->where('active', $this->parameters['active']);
        }
        $items = $query->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            return [
                'index' => $index + 1,
                'denomination' => $item->name,
                'category' => $item->category . '*',
                'hotelChain' => $item->hotelChain['name'],
                'country' => $item->country['name'],
                'state' => $item->state['name'],
                'city' => $item->city['name'],
                'postalCode' => $item->postal_code,
                'address' => $item->address,
                'email' => $item->email,
                'adminPhone' => $item->admin_phone,
                'adminFax' => $item->admin_fax,
                'webSite' => $item->web_site,
                'turisticLicence' => $item->turistic_licence,
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
            'Denomination',
            'Category',
            'Hotel Chain',
            'Country',
            'State',
            'City',
            'Postal Code',
            'Address',
            'Email',
            'Admin Phone',
            'Admin Fax',
            'Web Site',
            'Turistic Licence',
            'Enabled',
        ];
    }
}
