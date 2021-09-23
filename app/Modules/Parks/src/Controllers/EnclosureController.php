<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Request\EnclosureRequest;
use App\Modules\Parks\src\Resources\EnclosureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Parques - Cerramientos
 *
 * API para la gestión y consulta de datos de Tipos de Cerramientos.
 *
 */
class EnclosureController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Enclosure::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Enclosure::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Enclosure::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Cerramientos
     *
     * Cerramientos
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            EnclosureResource::collection( Enclosure::all() )
        );
    }

    /**
     * @group Parques - Cerramientos
     *
     * Crear Cerramientos
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
     * @param EnclosureRequest $request
     * @return JsonResponse
     */
    public function store(EnclosureRequest $request)
    {
        $form = new Enclosure();
        $form->Cerramiento = $request->get('name');
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Cerramientos
     *
     * Actualizar Cerramientos
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam enclosure int required Id del tipo de cerramiento: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param EnclosureRequest $request
     * @param Enclosure $enclosure
     * @return JsonResponse
     */
    public function update(EnclosureRequest $request, Enclosure $enclosure)
    {
        $enclosure->Cerramiento = $request->get('name');
        $enclosure->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Cerramientos
     *
     * Eliminar Cerramientos
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam enclosure required Id del tipo de cerramiento: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Enclosure $enclosure
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Enclosure $enclosure)
    {
        $enclosure->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
