<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Certified;
use App\Modules\Parks\src\Models\UpzType;
use App\Modules\Parks\src\Request\CertiicateStatusRequest;
use App\Modules\Parks\src\Request\UpzTypeRequest;
use App\Modules\Parks\src\Resources\CertificateStatusResource;
use App\Modules\Parks\src\Resources\UpzTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Parques - Estado de Certificación
 *
 * API para la gestión y consulta de datos de Estado de Certificación.
 *
 */
class CertifiedStatusController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Certified::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Certified::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Certified::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Estado de Certificación
     *
     * Estado de Certificación
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            CertificateStatusResource::collection( Certified::all() )
        );
    }

    /**
     * @group Parques - Estado de Certificación
     *
     * Crear Estado de Certificación
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
     * @param CertiicateStatusRequest $request
     * @return JsonResponse
     */
    public function store(CertiicateStatusRequest $request)
    {
        $form = new Certified();
        $form->EstadoCertificado = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Estado de Certificación
     *
     * Actualizar Estado de Certificación
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam certified int required Id del estado de certificación: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param CertiicateStatusRequest $request
     * @param Certified $certified
     * @return JsonResponse
     */
    public function update(CertiicateStatusRequest $request, Certified $certified)
    {
        $certified->EstadoCertificado = toUpper($request->get('name'));
        $certified->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Estado de Certificación
     *
     * Eliminar Estado de Certificación
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam certified int required Id del estado de certificación: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Certified $certified
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Certified $certified)
    {
        $certified->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
