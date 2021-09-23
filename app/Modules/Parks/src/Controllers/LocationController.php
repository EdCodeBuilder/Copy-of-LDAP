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

/**
 * @group Parques - Localidades
 *
 * API para la gestión y consulta de datos de Localidades.
 */
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
     * @group Parques - Localidades
     *
     * Localidades
     *
     * @queryParam column Nombre de la columna para realizar filtros u ordenamientos Ejemplo: ['name']. No-example
     * @queryParam order Orden de los resultados true para ascendente o false para descendente Ejemplo: ['true']. No-example
     * @queryParam where Indica que el valor a buscar debe ser igual al especifivado. No-example
     * @queryParam where_not Indica que el valor a buscar debe ser diferente al especifivado. No-example
     * @queryParam where_in Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: 1,2,3. No-example
     * @queryParam where_not_in Indica que el valor a buscar no debe estar entre los valores especificados Ejemplo: 1,2,3. No-example
     * @queryParam where_between Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: 2021-01-01,2021-05-31. No-example
     * @queryParam where_not_between Indica que el valor a buscar no debem estar entre los valores especificados Ejemplo: 1,8. No-example
     * @queryParam or_where Si está definido el parámetro where indicará que los valores a buscar deben ser iguales al primer valor entregado o igual al segundo valor. Ejemplo: api/ruta?column[]=id&where=1&or_where=2. No-example
     * @queryParam or_where_in Si está definido el parámetro where_in indicará que los valores a buscar deben estar en primer valor entregado o entre los datos del segundo valor. Ejemplo: api/ruta?column[]=id&where_in=1,2&or_where_in=5,6. No-example
     * @queryParam or_where_not_in Si está definido el parámetro where_in indicará que los valores a buscar no deben estar en primer valor entregado o entre los datos del segundo valor. Ejemplo: api/ruta?column[]=id&where_not_in=1,2&or_where_not_in=5,6. No-example
     * @queryParam or_where_between Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: api/ruta?column[]=id&where_between=2021-01-01,2021-05-31&or_where_between=2020-01-01,2020-05-31. No-example
     * @queryParam or_where_not_between Indica que el valor a buscar no debe estar entre los valores especificados Ejemplo: api/ruta?column[]=id&where_between=2021-01-01,2021-05-31&or_where_between=2020-01-01,2020-05-31. No-example
     *
     * Muestra un listado del recurso.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->setQuery(Location::query(), (new Location())->getSortableColumn($this->column))->get();
        return $this->success_response( LocationResource::collection( $data ) );
    }

    /**
     * @group Parques - Localidades
     *
     * Crear Localidad
     *
     * Almacena un recurso recién creado en la base de datos.
     *
     * @authenticated
     *
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
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

    /**
     * @group Parques - Localidades
     *
     * Actualizar Localidad
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam location int required Id de la localidad a modificar.
     *
     * @authenticated
     * @param LocationRequest $request
     * @param Location $location
     * @return JsonResponse
     *
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     */
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
     * @group Parques - Localidades
     *
     * Eliminar Localidad
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     * @authenticated
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
