<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
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
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Upz::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Upz::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Upz::class, 'destroy_or_manage'))->only('destroy');
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
                    'cod_upz'   =>  $request->get('upz_code'),
                    'Tipo'   =>  $request->get('upz_type_id'),
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
                'Tipo'   =>  $request->get('upz_type_id'),
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

    /**
     * @param $location
     * @param Upz $upz
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($location, Upz $upz)
    {
        $upz->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
