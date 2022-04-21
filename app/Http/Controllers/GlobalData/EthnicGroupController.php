<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\EthnicGroupResource;
use App\Models\Security\EthnicGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EthnicGroupController extends Controller
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
        $data = $this->setQuery(EthnicGroup::query(), (new EthnicGroup())->getKeyName())->get();
        return $this->success_response(
            EthnicGroupResource::collection( $data )
        );
    }
}
