<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\SexResource;
use App\Models\Security\Sex;

class SexController extends Controller
{
    public function index()
    {
        return $this->success_response(
            SexResource::collection( Sex::all() )
        );
    }
}
