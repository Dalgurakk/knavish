<?php

namespace App\Exports;

use App\Models\HotelPaxType;

class PaxTypeExport extends GeneralExport
{
    public function collection() {
        $query = HotelPaxType::orderBy('name');
        if ($this->parameters['name'] != '') {
            $query->where('name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if ($this->parameters['code'] != '') {
            $query->where('code', 'like', '%' . $this->parameters['code'] . '%');
        }
        if ($this->parameters['active'] != '') {
            $query->where('active', $this->parameters['active']);
        }
        $items = $query->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            return [
                'index' => $index + 1,
                'code' => $item->code,
                'denomination' => $item->name,
                'agefrom' => $item->agefrom,
                'ageto' => $item->ageto,
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
            'Code',
            'Denomination',
            'Age From',
            'Age To',
            'Enabled',
        ];
    }
}
