<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\AreaResource;
use App\Http\Resources\GlobalData\SubdirectorateResource;
use App\Models\Security\Subdirectorate;
use Illuminate\Http\Request;


class AreaController extends Controller
{
    public function office()
    {
        return $this->success_response( SubdirectorateResource::collection( Subdirectorate::all() ) );
    }

    public function areas(Subdirectorate $office)
    {
        return $this->success_response(
            AreaResource::collection( $office->areas )
        );
    }
}
