<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\PopulationGroupResource;
use App\Models\Security\PopulationGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PopulationGroupController extends Controller
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
        $data = $this->setQuery(PopulationGroup::query(), (new PopulationGroup())->getKeyName())->get();
        return $this->success_response(
            PopulationGroupResource::collection( $data )
        );
    }
}
