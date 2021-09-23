<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Rupi;
use App\Modules\Parks\src\Request\RupiRequest;
use App\Modules\Parks\src\Resources\RupiResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Parques - Vocaciones
 *
 * API para la gestión y consulta de datos de Tipos de Vocaciones.
 *
 */
class RupiController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Rupi::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Rupi::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Rupi::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Rupis
     *
     * Rupis
     *
     * Muestra un listado del recurso.
     *
     * RUPI: Es el código de identificación de los predios en el sistema de información de la Defensoría del Espacio Público.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     *
     * @param $park
     * @return JsonResponse
     */
    public function index($park)
    {
        $data = Park::with('rupis')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            return $this->success_response(
                RupiResource::collection( $data->rupis )
            );
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques - Rupis
     *
     * Crear Rupis
     *
     * Almacena un recurso recién creado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param $park
     * @param RupiRequest $request
     * @return JsonResponse
     */
    public function store($park, RupiRequest $request)
    {
        $data = Park::with('rupis')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            $data->rupis()->create([
               'Rupi'   => $request->get('name')
            ]);
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques - Rupis
     *
     * Actualizar Rupis
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam rupi int required Id del Ruoi. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param RupiRequest $request
     * @param $park
     * @param Rupi $rupi
     * @return JsonResponse
     */
    public function update(RupiRequest $request, $park, Rupi $rupi)
    {
        $rupi->fill([
            'Rupi'  => $request->get('name')
        ]);
        $rupi->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Rupis
     *
     * Eliminar Rupis
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam rupi int required Id del Ruoi. Example: 3
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param $park
     * @param Rupi $rupi
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Rupi $rupi)
    {
        $rupi->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
