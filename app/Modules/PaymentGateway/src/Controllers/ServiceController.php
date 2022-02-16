<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Models\ParkService;
use App\Modules\PaymentGateway\src\Models\ServiceOffered;
use App\Modules\PaymentGateway\src\Request\ServiceOfferedCreateUpdateRequest;
use App\Modules\PaymentGateway\src\Resources\ServicePseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class ServiceController extends Controller
{
      /**
       * Initialise common request params
       */
      public function __construct()
      {
            parent::__construct();
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Muestra un listado del recurso.
       *
       *
       * @return JsonResponse
       */
      public function index()
      {
            return $this->success_response(ServicePseResource::collection(ServiceOffered::all()));
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Crear un servicio con aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function create(ServiceOfferedCreateUpdateRequest $request)
      {
            $request->validated();
            $service = new ServiceOffered();
            $service->servicio_nombre = toUpper($request->name_service);
            $service->codigo_servicio = $request->code_service;
            $service->save();
            return $this->success_message(['service' => $service, 'message' => 'Servicio creado'], Response::HTTP_CREATED);
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Actualizar un servicio aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function update(ServiceOfferedCreateUpdateRequest $request, $id)
      {
            $request->validated();
            $service = ServiceOffered::find($id);
            $service->servicio_nombre = toUpper($request->name_service);
            $service->codigo_servicio = $request->code_service;
            $service->save();
            return $this->success_message(['service' => $service, 'message' => 'Servicio actualizado']);
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Eliminar un parque aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function delete($id)
      {
            $service = ServiceOffered::find($id);
            if ($service->parks) {
                  foreach ($service->parks as $park) {
                        ParkService::destroy($park->pivot->id_parque_servicio);
                  }
            }
            $service->destroy($id);

            return $this->success_message(['service_id' => $id, 'message' => 'Servicio eliminado']);
      }

      /**
       * @group Pasarela de pagos - Servicios
       *
       * servicios busqueda
       *
       * Muestra un listado de servicios por busqueda.
       *
       *
       * @return JsonResponse
       */
      public function search(Request $request)
      {
            $services = ServiceOffered::where('servicio_nombre', 'like', '%' . $request->word . '%')
                  ->orWhere('codigo_servicio', 'like', '%' .  $request->word . '%')
                  ->get();
            return $this->success_response(ServicePseResource::collection($services));
      }
}
