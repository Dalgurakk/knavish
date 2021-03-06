<?php

namespace App\Exports;

use App\Models\HotelContractClient;
use App\Models\Location;
use Carbon\Carbon;

class HotelContractClientExport extends GeneralExport
{
    public function collection() {

        $query = HotelContractClient::with([
            'hotelContract',
            'client',
            'priceRate',
            'priceRate.market',
            'hotelContract.hotel',
            'hotelContract.hotel.hotelChain',
            'hotelContract.hotel.country',
            'hotelContract.hotel.state',
            'hotelContract.hotel.city'
        ])
        ->orderBy('name');
        if ($this->parameters['name'] != '') {
            $query->where('name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if($this->parameters['validFrom'] != '') {
            $validFrom = Carbon::createFromFormat('d.m.Y', $this->parameters['validFrom']);
            $query->whereHas('hotelContract', function ($query) use ($validFrom) {
                $query->where('valid_from', '>=', $validFrom->format('Y-m-d'));
            });
        }
        if($this->parameters['validTo'] != '') {
            $validTo = Carbon::createFromFormat('d.m.Y', $this->parameters['validTo']);
            $query->whereHas('hotelContract', function ($query) use ($validTo) {
                $query->where('valid_to', '<=', $validTo->format('Y-m-d'));
            });
        }
        if($this->parameters['client'] != '') {
            $searchclient = $this->parameters['client'];
            $query->whereHas('client', function ($query) use ($searchclient) {
                $query->where('username', 'like', '%' . $searchclient . '%');
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
            $query->whereHas('hotelContract.hotel', function ($query) use ($allLocationIds) {
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
            $validFrom = Carbon::createFromFormat('Y-m-d', $item->hotelContract['valid_from']);
            $validTo = Carbon::createFromFormat('Y-m-d', $item->hotelContract['valid_to']);
            $priceRateValue = $item->priceRate['type'] == 1 ? ($item->priceRate['value'] . '%') : ('$' . $item->priceRate['value']);
            return [
                'index' => $index + 1,
                'denomination' => $item->name,
                'valid_from' => $validFrom->format('d.m.Y'),
                'valid_to' => $validTo->format('d.m.Y'),
                'client' => $item->client['name'],
                'priceRateName' => $item->priceRate['market']['name'],
                'priceRateValue' => $priceRateValue,
                'hotel' => $item->hotelContract['hotel']['name'],
                'category' => $item->hotelContract['hotel']['category'] . '*',
                'hotelChain' => $item->hotelContract['hotel']['hotelChain']['name'],
                'country' => $item->hotelContract['hotel']['country']['name'],
                'state' => $item->hotelContract['hotel']['state']['name'],
                'city' => $item->hotelContract['hotel']['city']['name'],
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
            'Client',
            'Price Rate Name',
            'Price Rate Value',
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
