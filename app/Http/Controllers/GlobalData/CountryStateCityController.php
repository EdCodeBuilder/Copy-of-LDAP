<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\CityResource;
use App\Http\Resources\GlobalData\CountryResource;
use App\Http\Resources\GlobalData\StateResource;
use App\Models\Security\CountryLDAP;
use App\Models\Security\StateLDAP;

class CountryStateCityController extends Controller
{
    public function countries()
    {
        return $this->success_response(
            CountryResource::collection( CountryLDAP::all() )
        );
    }

    public function states(CountryLDAP $country)
    {
        return $this->success_response(
            StateResource::collection( $country->states )
        );
    }

    public function cities($country, StateLDAP $state)
    {
        return $this->success_response(
            CityResource::collection( $state->cities )
        );
    }
}
