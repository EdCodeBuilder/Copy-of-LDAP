<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\StageType;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\StageTypeRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\StageTypeResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Parques - Tipos de Escenarios
 *
 * API para la gestión y consulta de datos de Tipos de Escenarios.
 *
 */
class StageTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(StageType::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(StageType::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(StageType::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Tipos de Escenarios
     *
     * Tipos de Escenarios
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( StageTypeResource::collection( StageType::all() ) );
    }

    /**
     * @group Parques - Tipos de Escenarios
     *
     * Crear Tipo de Escenario
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
     * @param StageTypeRequest $request
     * @return JsonResponse
     */
    public function store(StageTypeRequest $request)
    {
        $form = new StageType();
        $form->tipo = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Tipos de Escenarios
     *
     * Actualizar Tipo de Escenario
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam stage int required Id del tipo de escenario: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param StageTypeRequest $request
     * @param StageType $stage
     * @return JsonResponse
     */
    public function update(StageTypeRequest $request, StageType $stage)
    {
        $stage->tipo = $request->get('name');
        $stage->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Tipos de Escenarios
     *
     * Eliminar Tipo de Escenario
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam stage int required Id del tipo de escenario: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param StageType $stage
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(StageType $stage)
    {
        $stage->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
