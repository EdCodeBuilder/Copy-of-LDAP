<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\LocationRequest;
use App\Modules\Parks\src\Request\UpzRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Location::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Location::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Location::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->setQuery(Location::query(), 'Id_Localidad')->get();
        return $this->success_response( LocationResource::collection( $data ) );
    }

    /**
     * @param LocationRequest $request
     * @return JsonResponse
     */
    public function store(LocationRequest $request)
    {
        try {
            $form = new Location();
            $form->fill([
                'Localidad' => toUpper($request->get('name'))
            ]);
            $form->saveOrFail();
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

    public function update(LocationRequest $request, Location $location)
    {
        try {
            $location->fill([
                'Localidad' => toUpper($request->get('name'))
            ]);
            $location->saveOrFail();
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
     * @param Location $location
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
