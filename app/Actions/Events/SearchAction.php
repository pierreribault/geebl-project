<?php

namespace App\Actions\Events;

use App\Models\Event;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class SearchAction
{
    public function search(array $input): Paginator
    {
        Validator::make($input, [
            'query' => 'required|string|min:3',
            'city' => 'required|uuid|exists:cities,id'
        ])->validate();

        $query = $input['query'];
        $city = $input['city'];

        return Event::search($query)->where('city_id', $city)->paginate();
    }
}
