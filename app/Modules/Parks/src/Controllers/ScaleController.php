<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Request\ScaleRequest;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Parques - Escalas
 *
 * API para la gestión y consulta de datos de las Escalas de Parques.
 *
 */
class ScaleController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Scale::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Scale::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Scale::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Escalas
     *
     * Escalas
     *
     * Muestra un listado del recurso.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( ScaleResource::collection( Scale::all() ) );
    }

    /**
     * @group Parques - Escalas
     *
     * Crear Escalas
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
     * @param ScaleRequest $request
     * @return JsonResponse
     */
    public function store(ScaleRequest $request)
    {
        $form = new Scale();
        $form->Tipo = $request->get('name');
        $form->Descripcion = $request->get('description');
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Escalas
     *
     * Actualizar Escalas
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam scale int required Id de la escala: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param ScaleRequest $request
     * @param Scale $scale
     * @return JsonResponse
     */
    public function update(ScaleRequest $request, Scale $scale)
    {
        $scale->Tipo = $request->get('name');
        $scale->Descripcion = $request->get('description');
        $scale->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Escalas
     *
     * Eliminar Escalas
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam scale int required Id de la escala: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Scale $scale
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Scale $scale)
    {
        $scale->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
