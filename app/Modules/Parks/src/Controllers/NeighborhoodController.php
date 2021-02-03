<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\LocationRequest;
use App\Modules\Parks\src\Request\NeighborhoodRequest;
use App\Modules\Parks\src\Request\UpzRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NeighborhoodController extends Controller
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
     * @param Location $location
     * @param Upz $upz
     * @return JsonResponse
     */
    public function index($location, Upz $upz)
    {
        return $this->success_response(NeighborhoodResource::collection($upz->neighborhoods));
    }

    /**
     * @param NeighborhoodRequest $request
     * @param $location
     * @param Upz $upz
     * @return JsonResponse
     */
    public function store(NeighborhoodRequest $request, $location, Upz $upz)
    {
        try {
            $upz->neighborhoods()
                ->create([
                    'Barrio'       =>  $request->get('name'),
                    'CodUpz'   =>  $request->get('upz_code')
                ]);
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param NeighborhoodRequest $request
     * @param $location
     * @param $upz
     * @param Neighborhood $neighborhood
     * @return JsonResponse
     */
    public function update(NeighborhoodRequest $request, $location, $upz, Neighborhood $neighborhood)
    {
        try {
            $neighborhood->fill([
                'Barrio'       =>  $request->get('name'),
                'CodUpz'  =>  $request->get('upz_code')
            ]);
            $neighborhood->saveOrFail();
            return $this->success_message(
                __('validation.handler.updated')
            );
        } catch (\Throwable $e) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }
}
