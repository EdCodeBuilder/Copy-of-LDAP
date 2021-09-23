<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\UpzType;
use App\Modules\Parks\src\Request\UpzTypeRequest;
use App\Modules\Parks\src\Resources\UpzTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Parques - Tipos de Upz
 *
 * API para la gestión y consulta de datos de Tipos de Upz.
 *
 */
class UpzTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(UpzType::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(UpzType::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(UpzType::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Tipos de Upz
     *
     * Tipos de Upz
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            UpzTypeResource::collection( UpzType::all() )
        );
    }

    /**
     * @group Parques - Tipos de Upz
     *
     * Crear Tipo de Upz
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
     * @param UpzTypeRequest $request
     * @return JsonResponse
     */
    public function store(UpzTypeRequest $request)
    {
        $form = new UpzType();
        $form->Tipo = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Tipos de Upz
     *
     * Actualizar Tipo de Upz
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam types int required Id del tipo de Upz: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param UpzTypeRequest $request
     * @param UpzType $types
     * @return JsonResponse
     */
    public function update(UpzTypeRequest $request, UpzType $types)
    {
        $types->Tipo = toUpper($request->get('name'));
        $types->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Tipos de Upz
     *
     * Eliminar Tipo de Upz
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam types int required Id del tipo de Upz: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param UpzType $types
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(UpzType $types)
    {
        $types->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
