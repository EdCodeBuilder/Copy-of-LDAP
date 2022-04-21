<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\BloodTypeResource;
use App\Http\Resources\GlobalData\SexResource;
use App\Models\Security\BloodType;
use App\Models\Security\Sex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BloodTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->setQuery(BloodType::query(), (new BloodType())->getKeyName())->get();
        return $this->success_response(
            BloodTypeResource::collection( $data )
        );
    }
}
