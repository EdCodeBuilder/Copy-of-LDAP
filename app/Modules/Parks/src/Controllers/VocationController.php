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

/**
 * @group Parques - Vocaciones
 *
 * @groupDescription API para la gestión y consulta de datos de Tipos de Vocaciones.
 *
 */
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
     * @group Parques - Vocaciones
     *
     * Vocaciones
     *
     * Muestra un listado del recurso.
     *
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(VocationResource::collection(Vocation::all()));
    }

    /**
     * @group Parques - Vocaciones
     *
     * Crear Vocaciones
     *
     * Almacena un recurso recién creado en la base de datos.
     *
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
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
     * @group Parques - Vocaciones
     *
     * Actualizar Vocaciones
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam vocation int required Id del tipo de vocación: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
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
     * @group Parques - Vocaciones
     *
     * Eliminar Vocaciones
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam vocation int required Id del tipo de vocación: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
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
