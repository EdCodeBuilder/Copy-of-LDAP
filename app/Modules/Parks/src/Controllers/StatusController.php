<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Status;
use App\Modules\Parks\src\Request\StatusRequest;
use App\Modules\Parks\src\Resources\StatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Parques - Estados
 *
 * API para la gestión y consulta de datos de Estados.
 *
 */
class StatusController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Status::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Status::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Status::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Estados
     *
     * Estados
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( StatusResource::collection( Status::all() ) );
    }

    /**
     * @group Parques - Estados
     *
     * Crear Estados
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
     * @param StatusRequest $request
     * @return JsonResponse
     */
    public function store(StatusRequest $request)
    {
        $form = new Status();
        $form->Estado = $request->get('name');
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Estados
     *
     * Actualizar Estados
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam status int required Id del estado: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param StatusRequest $request
     * @param Status $status
     * @return JsonResponse
     */
    public function update(StatusRequest $request, Status $status)
    {
        $status->Estado = $request->get('name');
        $status->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Estados
     *
     * Eliminar Estados
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam status int required Id del estado: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Status $status
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @group Parques - Tipos de Zonas
     *
     * Tipos de Zonas
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function type_zones()
    {
        return $this->success_message([
            [
                'id'    =>  'RESIDENCIAL',
                'name'  =>  'RESIDENCIAL',
            ],
            [
                'id'    =>  'COMERCIAL',
                'name'  =>  'COMERCIAL',
            ],
            [
                'id'    =>  'SENDEROS',
                'name'  =>  'SENDEROS',
            ],
            [
                'id'    =>  'MIXTO',
                'name'  =>  'MIXTO',
            ],
            [
                'id'    =>  'RESIDENCIAL/COMERCIAL',
                'name'  =>  'RESIDENCIAL/COMERCIAL',
            ],
            [
                'id'    =>  'INDUSTRIAL/COMERCIAL',
                'name'  =>  'INDUSTRIAL/COMERCIAL',
            ],
            [
                'id'    =>  'RURAL',
                'name'  =>  'RURAL',
            ],
            [
                'id'    =>  'MONTAÑOSO',
                'name'  =>  'MONTAÑOSO',
            ],
            [
                'id'    =>  'INDUSTRIAL',
                'name'  =>  'INDUSTRIAL',
            ],
            [
                'id'    =>  'INSTITUCIONAL',
                'name'  =>  'INSTITUCIONAL',
            ],
            [
                'id'    =>  'ESCOLAR',
                'name'  =>  'ESCOLAR',
            ],
            [
                'id'    =>  'OTRO',
                'name'  =>  'OTRO',
            ],
            [
                'id'    =>  'NINGUNO',
                'name'  =>  'NINGUNO',
            ],
            [
                'id'    =>  'BUENO',
                'name'  =>  'BUENO',
            ],
            [
                'id'    =>  'MALO',
                'name'  =>  'MALO',
            ],
            [
                'id'    =>  'REGULAR',
                'name'  =>  'REGULAR',
            ],
        ]);
    }

    /**
     * @group Parques - Competencia/Regulación
     *
     * Competencia/Regulación
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function concerns()
    {
        return $this->success_message([
            [
                'id'    =>  'IDRD',
                'name'  =>  'IDRD'
            ],
            [
                'id'    =>  'Junta Administradora Local',
                'name'  =>  'Junta Administradora Local'
            ],
            [
                'id'    =>  'Alcaldía Local',
                'name'  =>  'Alcaldía Local'
            ],
            [
                'id'    =>  'Alianza Público Privada',
                'name'  =>  'Alianza Público Privada'
            ],
            [
                'id'    =>  'Indefinido',
                'name'  =>  'Indefinido'
            ],
            [
                'id'    =>  'Otros',
                'name'  =>  'Otros'
            ],
            [
                'id'    =>  'SI',
                'name'  =>  'SI'
            ],
            [
                'id'    =>  'NO',
                'name'  =>  'NO'
            ],
        ]);
    }

    /**
     * @group Parques - Vigilancia
     *
     * Vigilancia
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function vigilance()
    {
        $data = [
            [
                'id'    =>  'Sin vigilancia',
                'name'  =>  'Sin vigilancia'
            ],
            [
                'id'    =>  'Con vigilancia',
                'name'  =>  'Con vigilancia'
            ],
        ];
        return $this->success_message($data);
    }
}
