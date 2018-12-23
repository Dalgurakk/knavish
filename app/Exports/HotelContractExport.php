<?php

namespace App\Exports;

use App\Models\HotelContract;
use App\Models\Location;
use Carbon;

class HotelContractExport extends GeneralExport
{
    public function collection() {
        $query = HotelContract::with(
            'hotel',
            'hotel.hotelChain',
            'hotel.country',
            'hotel.state',
            'hotel.city'
        )->orderBy('name');
        if ($this->parameters['name'] != '') {
            $query->where('name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if($this->parameters['validFrom'] != '') {
            $validFrom = Carbon::createFromFormat('d.m.Y', $this->parameters['validFrom']);
            $query->where('hotel_contracts.valid_from', '>=', $validFrom->format('Y-m-d'));
        }
        if($this->parameters['validTo'] != '') {
            $validTo = Carbon::createFromFormat('d.m.Y', $this->parameters['validTo']);
            $query->where('hotel_contracts.valid_to', '<=', $validTo->format('Y-m-d'));
        }
        if($this->parameters['hotel'] != '') {
            $searchHotel = $this->parameters['hotel'];
            $query->whereHas('hotel', function ($query) use ($searchHotel) {
                $query->where('name', 'like', '%' . $searchHotel . '%');
            });
        }
        if ($this->parameters['active'] != '') {
            $query->where('active', $this->parameters['active']);
        }
        if($this->parameters['location'] != '') {
            $locations = Location::where('name', 'like', '%' . $this->parameters['location'] . '%')->get();
            $allLocationIds = array();
            foreach ($locations as $location) {
                $allLocations = Location::descendantsAndSelf($location->id);
                foreach ($allLocations as $aux) {
                    $allLocationIds[] = $aux->id;
                }
            }
            $query->whereHas('hotel', function ($query) use ($allLocationIds) {
                $query
                    ->whereHas('country', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    })
                    ->orWhereHas('state', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    })
                    ->orWhereHas('city', function ($query) use ($allLocationIds) {
                        $query->whereIn('id', $allLocationIds);
                    });
            });
        }
        $items = $query->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            $validFrom = Carbon::createFromFormat('Y-m-d', $item->valid_from);
            $validTo = Carbon::createFromFormat('Y-m-d', $item->valid_to);
            return [
                'index' => $index + 1,
                'denomination' => $item->name,
                'valid_from' => $validFrom->format('d.m.Y'),
                'valid_to' => $validTo->format('d.m.Y'),
                'hotel' => $item->hotel['name'],
                'category' => $item->hotel['category'] . '*',
                'hotelChain' => $item->hotel['hotelChain']['name'],
                'country' => $item->hotel['country']['name'],
                'state' => $item->hotel['state']['name'],
                'city' => $item->hotel['city']['name'],
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
            'Valid From',
            'Valid To',
            'Hotel',
            'Category',
            'Hotel Chain',
            'Country',
            'State',
            'City',
            'Enabled',
        ];
    }
}
