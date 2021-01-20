<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
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
    public function index(): JsonResponse
    {
        return $this->success_response( LocationResource::collection( Location::all() ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Location $location
     * @return JsonResponse
     */
    public function upz(Location $location): JsonResponse
    {
        return $this->success_response(UpzResource::collection($location->upz));
    }

    /**
     * Display a listing of the resource.
     *
     * @param $location
     * @param Upz $upz
     * @return JsonResponse
     */
    public function neighborhoods($location, Upz $upz): JsonResponse
    {
        return $this->success_response(NeighborhoodResource::collection($upz->neighborhoods));
    }
}
