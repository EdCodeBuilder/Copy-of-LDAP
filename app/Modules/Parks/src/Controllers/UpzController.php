<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\LocationRequest;
use App\Modules\Parks\src\Request\UpdateUpzRequest;
use App\Modules\Parks\src\Request\UpzRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\UpzLocalityResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Parques - UPZ
 *
 * API para la gestión y consulta de datos de UPZ.
 */
class UpzController extends Controller
{
      /**
       * Initialise common request params
       */
      public function __construct()
      {
            parent::__construct();
            $this->middleware('auth:api')->except('index');
            $this->middleware(Roles::actions(Upz::class, 'create_or_manage'))->only('store');
            $this->middleware(Roles::actions(Upz::class, 'update_or_manage'))->only('update');
            $this->middleware(Roles::actions(Upz::class, 'destroy_or_manage'))->only('destroy');
      }

      /**
       * @group Parques - UPZ
       *
       * UPZ
       *
       * Muestra un listado del recurso.
       *
       * @urlParam location int required Id de la localidad. Example: 1
       *
       * @param Location $location
       * @return JsonResponse
       */
      public function index(Location $location)
      {
            return $this->success_response(UpzResource::collection($location->upz));
      }

      /**
       * @group Parques - UPZ
       *
       * Crear UPZ
       *
       * Almacena un recurso recién creado en la base de datos.
       *
       * @urlParam location int required Id de la localidad. Example: 1
       *
       * @authenticated
       * @response 201 {
       *      "data": "Datos almacenados satisfactoriamente",
       *      "details": null,
       *      "code": 201,
       *      "requested_at": "2021-09-20T17:52:01-05:00"
       * }
       *
       * @param UpzRequest $request
       * @param Location $location
       * @return JsonResponse
       */
      public function store(UpzRequest $request, Location $location)
      {
            try {
                  $location->upz()
                        ->create([
                              'Upz'       =>  $request->get('name'),
                              'cod_upz'   =>  $request->get('upz_code'),
                              'Tipo'   =>  $request->get('upz_type_id'),
                        ]);
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
       * @group Parques - UPZ
       *
       * Actualizar UPZ
       *
       * Actualiza el recurso especificado en la base de datos.
       *
       * @urlParam location int required Id de la localidad. Example: 1
       * @urlParam upz int required Id de la UPZ. Example: 1
       *
       * @authenticated
       * @response {
       *      "data": "Datos actualizados satisfactoriamente",
       *      "details": null,
       *      "code": 200,
       *      "requested_at": "2021-09-20T17:52:01-05:00"
       * }
       *
       * @param UpdateUpzRequest $request
       * @param $location
       * @param Upz $upz
       * @return JsonResponse
       */
      public function update(UpdateUpzRequest $request, $location, Upz $upz)
      {
            try {
                  $upz->fill([
                        'Upz'           =>  $request->get('name'),
                        'cod_upz'       =>  $request->get('upz_code'),
                        'IdLocalidad'   =>  $request->get('locality_id'),
                        'Tipo'   =>  $request->get('upz_type_id'),
                  ]);
                  $upz->saveOrFail();
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
       * @group Parques - UPZ
       *
       * Eliminar UPZ
       *
       * Elimina el recurso especificado en la base de datos.
       *
       * @urlParam location int required Id de la localidad. Example: 1
       * @urlParam upz int required Id de la UPZ. Example: 1
       *
       * @authenticated
       * @response {
       *      "data": "Datos eliminados satisfactoriamente",
       *      "details": null,
       *      "code": 204,
       *      "requested_at": "2021-09-20T17:52:01-05:00"
       * }
       *
       *
       * @param $location
       * @param Upz $upz
       * @return JsonResponse
       * @throws \Exception
       */
      public function destroy($location, Upz $upz)
      {
            $upz->delete();
            return $this->success_message(
                  __('validation.handler.deleted'),
                  Response::HTTP_OK,
                  Response::HTTP_NO_CONTENT
            );
      }

      public function upzs()
      {
            $upzs = Upz::with('locality')->get();
            return $this->success_response(UpzLocalityResource::collection($upzs));
      }
}
