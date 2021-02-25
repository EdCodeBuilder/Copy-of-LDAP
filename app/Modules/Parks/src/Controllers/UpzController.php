<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\LocationRequest;
use App\Modules\Parks\src\Request\UpdateUpzRequest;
use App\Modules\Parks\src\Request\UpzRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpzController extends Controller
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
     * @return JsonResponse
     */
    public function index(Location $location)
    {
        return $this->success_response(UpzResource::collection($location->upz));
    }

    /**
     * @param UpzRequest $request
     * @param Location $location
     * @return JsonResponse
     */
    public function store(UpzRequest $request, Location $location)
    {
        try {
            $location->upz()
                ->create([
                    'Upz'       =>  $request->get('name'),
                    'cod_upz'   =>  $request->get('upz_code')
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
     * @param Request $request
     * @param $location
     * @param Upz $upz
     * @return JsonResponse
     */
    public function update(UpdateUpzRequest $request, $location, Upz $upz)
    {
        try {
            $upz->fill([
                'Upz'           =>  $request->get('name'),
                'cod_upz'       =>  $request->get('upz_code'),
                'IdLocalidad'   =>  $request->get('locality_id'),
            ]);
            $upz->saveOrFail();
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
