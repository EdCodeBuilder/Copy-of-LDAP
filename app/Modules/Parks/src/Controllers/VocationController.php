<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Models\Vocation;
use App\Modules\Parks\src\Request\LocationRequest;
use App\Modules\Parks\src\Request\UpdateUpzRequest;
use App\Modules\Parks\src\Request\UpzRequest;
use App\Modules\Parks\src\Request\VocationRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzResource;
use App\Modules\Parks\src\Resources\VocationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VocationController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Vocation::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Vocation::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Vocation::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(VocationResource::collection(Vocation::all()));
    }

    /**
     * @param VocationRequest $request
     * @return JsonResponse
     */
    public function store(VocationRequest $request)
    {
        try {
            $form = new Vocation();
            $form->fill([
                'vocacion'  =>  toUpper($request->get('name')),
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

    /**
     * @param VocationRequest $request
     * @param Vocation $vocation
     * @return JsonResponse
     */
    public function update(VocationRequest $request, Vocation $vocation)
    {
        try {
            $vocation->fill([
                'vocacion'  =>  toUpper($request->get('name')),
            ]);
            $vocation->saveOrFail();
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
     * @param Vocation $vocation
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Vocation $vocation)
    {
        $vocation->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
