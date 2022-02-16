<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Models\ParkService;
use App\Modules\PaymentGateway\src\Request\AssignServicesRequest;
use App\Modules\PaymentGateway\src\Resources\ParkServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class ParkServiceController extends Controller
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
            $parks_services = ParkService::with('park', 'service')->get();
            return $this->success_response(ParkServiceResource::collection($parks_services));
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Asigna servicio a un parque con aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function assign(AssignServicesRequest $request)
      {
            $park_service = new ParkService;
            $park_service->id_parque = $request->park_id;
            $park_service->id_servicio = $request->service_id;
            $park_service->save();
            $park_service_assign = $park_service->with('park', 'service')->where('id_parque_servicio', $park_service->id_parque_servicio)->get();
            return $this->success_response(ParkServiceResource::collection($park_service_assign), Response::HTTP_OK, 'Servicio asignado');
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * actualiza servicio a un parque con aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function update(AssignServicesRequest $request, $id)
      {
            $park_service = ParkService::find($id);
            $park_service->id_parque = $request->park_id;
            $park_service->id_servicio = $request->service_id;
            $park_service->save();
            $park_service_assign_update = $park_service->with('park', 'service')->where('id_parque_servicio', $id)->get();
            return $this->success_response(ParkServiceResource::collection($park_service_assign_update), Response::HTTP_OK, 'Servicio actualizado');
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Elimina un parque-servicio aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function delete($id)
      {
            ParkService::destroy($id);
            return $this->success_message(['park_service_id' => $id, 'message' => 'Parque_servicio eliminado']);
      }

      /**
       * @group Pasarela de pagos - Servicios-parques
       *
       * servicios-parques busqueda
       *
       * Muestra un listado de servicios-parques por busqueda.
       *
       *
       * @return JsonResponse
       */
      public function search(Request $request)
      {
            $parks_services = ParkService::with('park', 'service')
                  ->join('parque', 'parque.id_parque', '=', 'parque_servicio.id_parque')
                  ->join('servicio', 'servicio.id_servicio', '=', 'parque_servicio.id_servicio')
                  ->where('parque.nombre_parque', 'LIKE', "%$request->word%")
                  ->orWhere('servicio.servicio_nombre', 'LIKE', "%$request->word%")
                  ->get();
            return $this->success_response(ParkServiceResource::collection($parks_services));
      }
}
